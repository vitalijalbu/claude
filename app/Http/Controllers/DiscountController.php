<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Actions\Discounts\IndexDiscountsAction;
use App\Actions\Discounts\ShowDiscountAction;
use App\Actions\Discounts\ValidateCouponAction;
use Lunar\Models\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DiscountController extends Controller
{
    public function index(IndexDiscountsAction $action): JsonResponse
    {
        $discounts = $action->execute(['per_page' => request('per_page', 15)]);
        
        return response()->json([
            'data' => DiscountResource::collection($discounts->items()),
            'meta' => [
                'current_page' => $discounts->currentPage(),
                'last_page' => $discounts->lastPage(),
                'per_page' => $discounts->perPage(),
                'total' => $discounts->total()
            ]
        ]);
    }

    public function show(Discount $discount, ShowDiscountAction $action): JsonResponse
    {
        $discount = $action->execute($discount);
        return response()->json(['data' => new DiscountResource($discount)]);
    }

    public function validateCoupon(Request $request, ValidateCouponAction $action): JsonResponse
    {
        $request->validate([
            'coupon' => 'required|string'
        ]);

        $result = $action->execute($request->coupon);

        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'data' => new DiscountResource($result['discount']),
            'message' => $result['message']
        ]);
    }
}

// app/Http/Controllers/Api/GrabberController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GrabberRequest;
use App\Actions\Grabber\ProcessGrabberDataAction;
use App\Actions\Grabber\IndexGrabberLogsAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrabberController extends Controller
{
    public function __construct()
    {
        // Add API key authentication middleware here if needed
        // $this->middleware('auth.api_key');
    }

    public function process(GrabberRequest $request, ProcessGrabberDataAction $action): JsonResponse
    {
        $result = $action->execute(
            type: $request->validated('type'),
            action: $request->validated('action'),
            externalId: $request->validated('external_id'),
            data: $request->validated('data')
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Data processed successfully',
                'log_id' => $result['log_id']
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
            'log_id' => $result['log_id']
        ], 400);
    }

    public function batchProcess(Request $request, ProcessGrabberDataAction $action): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|max:100',
            'items.*.type' => 'required|in:product,brand,collection',
            'items.*.action' => 'required|in:create,update',
            'items.*.external_id' => 'required|string',
            'items.*.data' => 'required|array'
        ]);

        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($request->items as $item) {
            $result = $action->execute(
                type: $item['type'],
                action: $item['action'],
                externalId: $item['external_id'],
                data: $item['data']
            );

            $results[] = [
                'external_id' => $item['external_id'],
                'success' => $result['success'],
                'log_id' => $result['log_id'],
                'error' => $result['error'] ?? null
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return response()->json([
            'success' => $failureCount === 0,
            'processed' => count($results),
            'successful' => $successCount,
            'failed' => $failureCount,
            'results' => $results
        ]);
    }

    public function logs(Request $request, IndexGrabberLogsAction $action): JsonResponse
    {
        $filters = [
            'type' => $request->type,
            'status' => $request->status,
            'external_id' => $request->external_id,
            'per_page' => $request->per_page ?? 15
        ];

        $logs = $action->execute($filters);

        return response()->json([
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total()
            ]
        ]);
    }
}

// =============================================================================
// ROUTE MODEL BINDING CUSTOMIZATION
// =============================================================================

// app/Providers/RouteServiceProvider.php - Add to boot method
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Lunar\Models\Product;
use Lunar\Models\Brand;
use Lunar\Models\Collection;
use Lunar\Models\Discount;
use Lunar\Models\CartLine;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        // Custom route model binding for better API experience
        Route::bind('product', function ($value) {
            return Product::where('id', $value)
                ->orWhere('slug', $value)
                ->firstOrFail();
        });

        Route::bind('brand', function ($value) {
            return Brand::where('id', $value)
                ->orWhere('slug', $value)
                ->firstOrFail();
        });

        Route::bind('collection', function ($value) {
            return Collection::where('id', $value)
                ->orWhere('slug', $value)
                ->firstOrFail();
        });

        Route::bind('discount', function ($value) {
            return Discount::where('id', $value)
                ->orWhere('handle', $value)
                ->firstOrFail();
        });
    }
}

// =============================================================================
// ADDITIONAL FILTER CLASSES
// =============================================================================

// app/Http/Filters/BrandFilter.php
namespace App\Http\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class BrandFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return match ($property) {
            'name' => $query->where('name', 'like', "%{$value}%"),
            'has_products' => $value ? $query->has('products') : $query->doesntHave('products'),
            default => $query
        };
    }
}

// app/Http/Filters/CollectionFilter.php
namespace App\Http\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CollectionFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return match ($property) {
            'name' => $query->where('name', 'like', "%{$value}%"),
            'parent' => $query->where('parent_id', $value),
            'root' => $value ? $query->whereNull('parent_id') : $query->whereNotNull('parent_id'),
            'has_products' => $value ? $query->has('products') : $query->doesntHave('products'),
            default => $query
        };
    }
}

// =============================================================================
// ENHANCED ACTIONS WITH FILTERS
// =============================================================================

// app/Actions/Brands/IndexBrandsAction.php (Enhanced)
namespace App\Actions\Brands;

use Lunar\Models\Brand;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Filters\BrandFilter;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexBrandsAction
{
    public function execute(array $params = []): LengthAwarePaginator
    {
        return QueryBuilder::for(Brand::class)
            ->allowedFilters([
                AllowedFilter::custom('name', new BrandFilter()),
                AllowedFilter::custom('has_products', new BrandFilter()),
                AllowedFilter::partial('name')
            ])
            ->allowedSorts(['name', 'created_at', 'updated_at'])
            ->withCount('products')
            ->paginate($params['per_page'] ?? 15);
    }
}

// app/Actions/Collections/IndexCollectionsAction.php (Enhanced)
namespace App\Actions\Collections;

use Lunar\Models\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Filters\CollectionFilter;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexCollectionsAction
{
    public function execute(array $params = []): LengthAwarePaginator
    {
        return QueryBuilder::for(Collection::class)
            ->allowedFilters([
                AllowedFilter::custom('name', new CollectionFilter()),
                AllowedFilter::custom('parent', new CollectionFilter()),
                AllowedFilter::custom('root', new CollectionFilter()),
                AllowedFilter::custom('has_products', new CollectionFilter()),
                AllowedFilter::partial('name')
            ])
            ->allowedSorts(['name', 'created_at', 'updated_at'])
            ->allowedIncludes(['parent', 'children'])
            ->with(['parent', 'children'])
            ->withCount('products')
            ->paginate($params['per_page'] ?? 15);
    }
}

// =============================================================================
// ADDITIONAL VALIDATION RULES
// =============================================================================

// app/Rules/ValidProductVariant.php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Lunar\Models\ProductVariant;

class ValidProductVariant implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!ProductVariant::find($value)) {
            $fail('The selected product variant is invalid.');
        }
    }
}

// app/Rules/ValidCouponCode.php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Lunar\Models\Discount;

class ValidCouponCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $discount = Discount::where('coupon', $value)
            ->where('starts_at', '<=', now())
            ->where(function($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
            })
            ->first();

        if (!$discount) {
            $fail('The coupon code is invalid or expired.');
        }
    }
}

// =============================================================================
// EXCEPTION HANDLING
// =============================================================================

// app/Exceptions/CartException.php
namespace App\Exceptions;

use Exception;

class CartException extends Exception
{
    public static function itemNotFound(): self
    {
        return new self('Cart item not found.');
    }

    public static function invalidQuantity(): self
    {
        return new self('Invalid quantity specified.');
    }

    public static function couponAlreadyApplied(): self
    {
        return new self('A coupon is already applied to this cart.');
    }
}

// app/Exceptions/GrabberException.php
namespace App\Exceptions;

use Exception;

class GrabberException extends Exception
{
    public static function invalidExternalId(): self
    {
        return new self('Invalid external ID provided.');
    }

    public static function processingFailed(string $message): self
    {
        return new self("Processing failed: {$message}");
    }

    public static function unsupportedType(string $type): self
    {
        return new self("Unsupported type: {$type}");
    }
}

// =============================================================================
// RESPONSE TRAITS
// =============================================================================

// app/Traits/ApiResponse.php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data = null, string $message = '', int $code = 200): JsonResponse
    {
        $response = ['success' => true];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        if ($message) {
            $response['message'] = $message;
        }
        
        return response()->json($response, $code);
    }

    protected function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if ($errors) {
            $response['errors'] = $errors;
        }
        
        return response()->json($response, $code);
    }

    protected function paginatedResponse($data, string $message = ''): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ]
        ];
        
        if ($message) {
            $response['message'] = $message;
        }
        
        return response()->json($response);
    }
}

// =============================================================================
// TESTS EXAMPLES
// =============================================================================

/*
// tests/Feature/ProductApiTest.php
namespace Tests\Feature;

use Tests\TestCase;
use Lunar\Models\Product;
use Lunar\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        Product::factory(5)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'slug', 'description']
                    ],
                    'meta' => ['current_page', 'last_page', 'per_page', 'total']
                ]);
    }

    public function test_can_filter_products_by_brand()
    {
        $brand = Brand::factory()->create(['slug' => 'nike']);
        Product::factory()->create(['brand_id' => $brand->id]);
        Product::factory()->create(); // Different brand

        $response = $this->getJson('/api/v1/products?filter[brand]=nike');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_can_create_product()
    {
        $brand = Brand::factory()->create();

        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'brand_id' => $brand->id,
            'variants' => [
                [
                    'sku' => 'TEST-001',
                    'price' => 1000
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response->assertStatus(201)
                ->assertJsonFragment(['name' => 'Test Product']);
    }
}

// tests/Feature/CartApiTest.php
namespace Tests\Feature;

use Tests\TestCase;
use Lunar\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_cart()
    {
        $response = $this->postJson('/api/v1/cart');

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'session_id'],
                    'session_id'
                ]);
    }

    public function test_can_add_item_to_cart()
    {
        $variant = ProductVariant::factory()->create();
        $sessionId = 'test-session-123';

        $data = [
            'purchasable_id' => $variant->id,
            'purchasable_type' => 'Lunar\Models\ProductVariant',
            'quantity' => 2
        ];

        $response = $this->postJson('/api/v1/cart/items', $data, [
            'X-Cart-Session' => $sessionId
        ]);

        $response->assertStatus(200)
                ->assertJsonFragment(['quantity' => 2]);
    }
}
*/CollectionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionRequest;
use App\Http\Resources\CollectionResource;
use App\DTOs\CollectionDTO;
use App\Actions\Collections\IndexCollectionsAction;
use App\Actions\Collections\ShowCollectionAction;
use App\Actions\Collections\CreateCollectionAction;
use App\Actions\Collections\UpdateCollectionAction;
use App\Actions\Collections\DeleteCollectionAction;
use Lunar\Models\Collection;
use Illuminate\Http\JsonResponse;

class CollectionController extends Controller
{
    public function index(IndexCollectionsAction $action): JsonResponse
    {
        $collections = $action->execute(['per_page' => request('per_page', 15)]);
        
        return response()->json([
            'data' => CollectionResource::collection($collections->items()),
            'meta' => [
                'current_page' => $collections->currentPage(),
                'last_page' => $collections->lastPage(),
                'per_page' => $collections->perPage(),
                'total' => $collections->total()
            ]
        ]);
    }

    public function show(Collection $collection, ShowCollectionAction $action): JsonResponse
    {
        $collection = $action->execute($collection);
        return response()->json(['data' => new CollectionResource($collection)]);
    }

    public function store(CollectionRequest $request, CreateCollectionAction $action): JsonResponse
    {
        $dto = CollectionDTO::fromRequest($request->validated());
        $collection = $action->execute($dto);
        
        return response()->json([
            'data' => new CollectionResource($collection),
            'message' => 'Collection created successfully'
        ], 201);
    }

    public function update(CollectionRequest $request, Collection $collection, UpdateCollectionAction $action): JsonResponse
    {
        $dto = CollectionDTO::fromRequest($request->validated());
        $collection = $action->execute($collection, $dto);
        
        return response()->json([
            'data' => new CollectionResource($collection),
            'message' => 'Collection updated successfully'
        ]);
    }

    public function destroy(Collection $collection, DeleteCollectionAction $action): JsonResponse
    {
        $action->execute($collection);
        
        return response()->json([
            'message' => 'Collection deleted successfully'
        ]);
    }
}

// app/Http/Controllers/Api/CartController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartItemRequest;
use App\Http\Resources\CartResource;
use App\DTOs\CartDTO;
use App\DTOs\CartItemDTO;
use App\Actions\Cart\ShowCartAction;
use App\Actions\Cart\CreateCartAction;
use App\Actions\Cart\AddItemToCartAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Actions\Cart\RemoveCartItemAction;
use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\ApplyCouponAction;
use App\Actions\Cart\RemoveCouponAction;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;

class CartController extends Controller
{
    public function show(Request $request, ShowCartAction $action): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        $cart = $action->execute($sessionId, $request->user()?->id);

        return response()->json(['data' => new CartResource($cart)]);
    }

    public function store(Request $request, CreateCartAction $action): JsonResponse
    {
        $sessionId = Uuid::uuid4()->toString();
        $dto = new CartDTO(
            session_id: $sessionId,
            user_id: $request->user()?->id
        );
        
        $cart = $action->execute($dto);
        
        return response()->json([
            'data' => new CartResource($cart),
            'session_id' => $sessionId,
            'message' => 'Cart created successfully'
        ], 201);
    }

    public function addItem(CartItemRequest $request, ShowCartAction $showAction, AddItemToCartAction $addAction): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        $cart = $showAction->execute($sessionId, $request->user()?->id);

        $dto = CartItemDTO::fromRequest($request->validated());
        $addAction->execute($cart, $dto);
        
        $cart->load(['lines.purchasable', 'discounts']);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Item added to cart successfully'
        ]);
    }

    public function updateItem(Request $request, CartLine $cartLine, UpdateCartItemAction $action): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $action->execute($cartLine, $request->quantity);
        $cart = $cartLine->cart->load(['lines.purchasable', 'discounts']);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Cart item updated successfully'
        ]);
    }

    public function removeItem(CartLine $cartLine, RemoveCartItemAction $action): JsonResponse
    {
        $cart = $cartLine->cart;
        $action->execute($cartLine);
        
        $cart->load(['lines.purchasable', 'discounts']);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Item removed from cart successfully'
        ]);
    }

    public function clear(Request $request, ClearCartAction $action): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        $cart = $action->execute($sessionId);
        
        return response()->json([
            'data' => $cart ? new CartResource($cart) : null,
            'message' => 'Cart cleared successfully'
        ]);
    }

    public function applyCoupon(Request $request, ShowCartAction $showAction, ApplyCouponAction $action): JsonResponse
    {
        $request->validate([
            'coupon' => 'required|string'
        ]);

        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        $cart = $showAction->execute($sessionId, $request->user()?->id);

        try {
            $cart = $action->execute($cart, $request->coupon);
            
            return response()->json([
                'data' => new CartResource($cart),
                'message' => 'Coupon applied successfully'
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function removeCoupon(Request $request, ShowCartAction $showAction, RemoveCouponAction $action): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        $cart = $showAction->execute($sessionId, $request->user()?->id);

        $cart = $action->execute($cart);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Coupon removed successfully'
        ]);
    }
}

// app/Http/Controllers/Api/<?php

// composer.json dependencies (add these to your existing composer.json)
/*
{
    "require": {
        "lunarphp/lunar": "^1.0@alpha",
        "spatie/laravel-query-builder": "^5.0",
        "ramsey/uuid": "^4.0"
    }
}
*/

// =============================================================================
// MIGRATIONS
// =============================================================================

// database/migrations/xxxx_create_grabber_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrabberLogsTable extends Migration
{
    public function up()
    {
        Schema::create('grabber_logs', function (Blueprint $table) {
            $table->id();
            $table->string('external_id');
            $table->string('type'); // product, brand, collection
            $table->string('action'); // create, update
            $table->json('payload');
            $table->string('status'); // success, failed
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grabber_logs');
    }
}

// =============================================================================
// MODELS
// =============================================================================

// app/Models/GrabberLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrabberLog extends Model
{
    protected $fillable = [
        'external_id',
        'type',
        'action',
        'payload',
        'status',
        'error_message'
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}

// =============================================================================
// DTOs
// =============================================================================

// app/DTOs/ProductDTO.php
namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
        public readonly ?array $attribute_data = [],
        public readonly ?int $brand_id = null,
        public readonly ?array $collection_ids = [],
        public readonly ?string $status = 'published',
        public readonly ?array $variants = [],
        public readonly ?string $external_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            attribute_data: $data['attribute_data'] ?? [],
            brand_id: $data['brand_id'] ?? null,
            collection_ids: $data['collection_ids'] ?? [],
            status: $data['status'] ?? 'published',
            variants: $data['variants'] ?? [],
            external_id: $data['external_id'] ?? null
        );
    }
}

// app/DTOs/BrandDTO.php
namespace App\DTOs;

class BrandDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
        public readonly ?string $external_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            external_id: $data['external_id'] ?? null
        );
    }
}

// app/DTOs/CollectionDTO.php
namespace App\DTOs;

class CollectionDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
        public readonly ?int $parent_id = null,
        public readonly ?string $external_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            parent_id: $data['parent_id'] ?? null,
            external_id: $data['external_id'] ?? null
        );
    }
}

// app/DTOs/CartDTO.php
namespace App\DTOs;

class CartDTO
{
    public function __construct(
        public readonly ?string $session_id = null,
        public readonly ?int $user_id = null,
        public readonly ?array $meta = []
    ) {}
}

// app/DTOs/CartItemDTO.php
namespace App\DTOs;

class CartItemDTO
{
    public function __construct(
        public readonly int $purchasable_id,
        public readonly string $purchasable_type,
        public readonly int $quantity,
        public readonly ?array $meta = []
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            purchasable_id: $data['purchasable_id'],
            purchasable_type: $data['purchasable_type'],
            quantity: $data['quantity'],
            meta: $data['meta'] ?? []
        );
    }
}

// =============================================================================
// REQUESTS
// =============================================================================

// app/Http/Requests/ProductRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $this->route('product')?->id,
            'description' => 'nullable|string',
            'attribute_data' => 'nullable|array',
            'brand_id' => 'nullable|exists:brands,id',
            'collection_ids' => 'nullable|array',
            'collection_ids.*' => 'exists:collections,id',
            'status' => 'nullable|in:published,draft,archived',
            'variants' => 'nullable|array',
            'external_id' => 'nullable|string'
        ];
    }
}

// app/Http/Requests/BrandRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:brands,slug,' . $this->route('brand')?->id,
            'description' => 'nullable|string',
            'external_id' => 'nullable|string'
        ];
    }
}

// app/Http/Requests/CollectionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:collections,slug,' . $this->route('collection')?->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:collections,id',
            'external_id' => 'nullable|string'
        ];
    }
}

// app/Http/Requests/CartItemRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchasable_id' => 'required|integer',
            'purchasable_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'meta' => 'nullable|array'
        ];
    }
}

// app/Http/Requests/GrabberRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrabberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:product,brand,collection',
            'action' => 'required|in:create,update',
            'external_id' => 'required|string',
            'data' => 'required|array'
        ];
    }
}

// =============================================================================
// ACTIONS
// =============================================================================

// app/Actions/Products/IndexProductsAction.php
namespace App\Actions\Products;

use Lunar\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Filters\ProductFilter;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexProductsAction
{
    public function execute(array $params = []): LengthAwarePaginator
    {
        return QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::custom('brand', new ProductFilter()),
                AllowedFilter::custom('collection', new ProductFilter()),
                AllowedFilter::custom('status', new ProductFilter()),
                AllowedFilter::custom('price_min', new ProductFilter()),
                AllowedFilter::custom('price_max', new ProductFilter()),
                AllowedFilter::partial('name'),
                AllowedFilter::exact('status')
            ])
            ->allowedSorts(['name', 'created_at', 'updated_at'])
            ->allowedIncludes(['brand', 'collections', 'variants'])
            ->with(['brand', 'collections'])
            ->paginate($params['per_page'] ?? 15);
    }
}

// app/Actions/Products/ShowProductAction.php
namespace App\Actions\Products;

use Lunar\Models\Product;

class ShowProductAction
{
    public function execute(Product $product): Product
    {
        return $product->load(['brand', 'collections', 'variants']);
    }
}

// app/Actions/Products/DeleteProductAction.php
namespace App\Actions\Products;

use Lunar\Models\Product;

class DeleteProductAction
{
    public function execute(Product $product): bool
    {
        return $product->delete();
    }
}

// app/Actions/Products/CreateProductAction.php
namespace App\Actions\Products;

use App\DTOs\ProductDTO;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Illuminate\Support\Str;

class CreateProductAction
{
    public function execute(ProductDTO $dto): Product
    {
        $product = Product::create([
            'name' => $dto->name,
            'slug' => $dto->slug ?: Str::slug($dto->name),
            'description' => $dto->description,
            'attribute_data' => $dto->attribute_data,
            'brand_id' => $dto->brand_id,
            'status' => $dto->status
        ]);

        // Attach collections
        if (!empty($dto->collection_ids)) {
            $product->collections()->attach($dto->collection_ids);
        }

        // Create variants
        if (!empty($dto->variants)) {
            foreach ($dto->variants as $variantData) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'] ?? 0,
                    'attribute_data' => $variantData['attribute_data'] ?? []
                ]);
            }
        }

        return $product->load(['brand', 'collections', 'variants']);
    }
}

// app/Actions/Products/UpdateProductAction.php
namespace App\Actions\Products;

use App\DTOs\ProductDTO;
use Lunar\Models\Product;
use Illuminate\Support\Str;

class UpdateProductAction
{
    public function execute(Product $product, ProductDTO $dto): Product
    {
        $product->update([
            'name' => $dto->name ?? $product->name,
            'slug' => $dto->slug ?? ($dto->name ? Str::slug($dto->name) : $product->slug),
            'description' => $dto->description ?? $product->description,
            'attribute_data' => $dto->attribute_data ?? $product->attribute_data,
            'brand_id' => $dto->brand_id ?? $product->brand_id,
            'status' => $dto->status ?? $product->status
        ]);

        // Update collections
        if ($dto->collection_ids !== null) {
            $product->collections()->sync($dto->collection_ids);
        }

        return $product->load(['brand', 'collections', 'variants']);
    }
}

// app/Actions/Brands/CreateBrandAction.php
namespace App\Actions\Brands;

use App\DTOs\BrandDTO;
use Lunar\Models\Brand;
use Illuminate\Support\Str;

class CreateBrandAction
{
    public function execute(BrandDTO $dto): Brand
    {
        return Brand::create([
            'name' => $dto->name,
            'slug' => $dto->slug ?: Str::slug($dto->name),
            'description' => $dto->description
        ]);
    }
}

// app/Actions/Brands/IndexBrandsAction.php
namespace App\Actions\Brands;

use Lunar\Models\Brand;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexBrandsAction
{
    public function execute(array $params = []): LengthAwarePaginator
    {
        return Brand::withCount('products')
            ->paginate($params['per_page'] ?? 15);
    }
}

// app/Actions/Brands/ShowBrandAction.php
namespace App\Actions\Brands;

use Lunar\Models\Brand;

class ShowBrandAction
{
    public function execute(Brand $brand): Brand
    {
        return $brand->loadCount('products');
    }
}

// app/Actions/Brands/UpdateBrandAction.php
namespace App\Actions\Brands;

use App\DTOs\BrandDTO;
use Lunar\Models\Brand;
use Illuminate\Support\Str;

class UpdateBrandAction
{
    public function execute(Brand $brand, BrandDTO $dto): Brand
    {
        $brand->update([
            'name' => $dto->name ?? $brand->name,
            'slug' => $dto->slug ?? ($dto->name ? Str::slug($dto->name) : $brand->slug),
            'description' => $dto->description ?? $brand->description
        ]);

        return $brand;
    }
}

// app/Actions/Collections/IndexCollectionsAction.php
namespace App\Actions\Collections;

use Lunar\Models\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexCollectionsAction
{
    public function execute(array $params = []): LengthAwarePaginator
    {
        return Collection::with(['parent', 'children'])
            ->withCount('products')
            ->paginate($params['per_page'] ?? 15);
    }
}

// app/Actions/Collections/ShowCollectionAction.php
namespace App\Actions\Collections;

use Lunar\Models\Collection;

class ShowCollectionAction
{
    public function execute(Collection $collection): Collection
    {
        return $collection->load(['parent', 'children'])->loadCount('products');
    }
}

// app/Actions/Cart/ShowCartAction.php
namespace App\Actions\Cart;

use Lunar\Models\Cart;
use App\DTOs\CartDTO;

class ShowCartAction
{
    public function execute(string $sessionId, ?int $userId = null): Cart
    {
        $cart = Cart::where('session_id', $sessionId)
            ->with(['lines.purchasable', 'discounts'])
            ->first();

        if (!$cart) {
            // Create new cart if not exists
            $dto = new CartDTO(session_id: $sessionId, user_id: $userId);
            $cart = (new CreateCartAction())->execute($dto);
            $cart->load(['lines.purchasable', 'discounts']);
        }

        return $cart;
    }
}

// app/Actions/Cart/UpdateCartItemAction.php
namespace App\Actions\Cart;

use Lunar\Models\CartLine;

class UpdateCartItemAction
{
    public function execute(CartLine $cartLine, int $quantity): CartLine
    {
        $cartLine->update(['quantity' => $quantity]);
        return $cartLine;
    }
}

// app/Actions/Cart/RemoveCartItemAction.php
namespace App\Actions\Cart;

use Lunar\Models\CartLine;

class RemoveCartItemAction
{
    public function execute(CartLine $cartLine): bool
    {
        return $cartLine->delete();
    }
}

// app/Actions/Cart/ClearCartAction.php
namespace App\Actions\Cart;

use Lunar\Models\Cart;

class ClearCartAction
{
    public function execute(string $sessionId): ?Cart
    {
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if ($cart) {
            $cart->lines()->delete();
            $cart->load(['lines.purchasable', 'discounts']);
        }
        
        return $cart;
    }
}

// app/Actions/Cart/ApplyCouponAction.php
namespace App\Actions\Cart;

use Lunar\Models\Cart;
use Lunar\Models\Discount;

class ApplyCouponAction
{
    public function execute(Cart $cart, string $couponCode): Cart
    {
        // Validate coupon exists and is active
        $discount = Discount::where('coupon', $couponCode)
            ->where('starts_at', '<=', now())
            ->where(function($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
            })
            ->first();

        if (!$discount) {
            throw new \InvalidArgumentException('Invalid or expired coupon code');
        }

        $cart->coupon_code = $couponCode;
        $cart->save();
        
        return $cart->load(['lines.purchasable', 'discounts']);
    }
}

// app/Actions/Discounts/IndexDiscountsAction.php
namespace App\Actions\Discounts;

use Lunar\Models\Discount;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexDiscountsAction
{
    public function execute(array $params = []): LengthAwarePaginator
    {
        return Discount::where('starts_at', '<=', now())
            ->where(function($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
            })
            ->paginate($params['per_page'] ?? 15);
    }
}

// app/Actions/Discounts/ShowDiscountAction.php
namespace App\Actions\Discounts;

use Lunar\Models\Discount;

class ShowDiscountAction
{
    public function execute(Discount $discount): Discount
    {
        return $discount;
    }
}

// app/Actions/Discounts/ValidateCouponAction.php
namespace App\Actions\Discounts;

use Lunar\Models\Discount;

class ValidateCouponAction
{
    public function execute(string $couponCode): array
    {
        $discount = Discount::where('coupon', $couponCode)
            ->where('starts_at', '<=', now())
            ->where(function($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
            })
            ->first();

        if (!$discount) {
            return [
                'valid' => false,
                'discount' => null,
                'message' => 'Invalid or expired coupon code'
            ];
        }

        return [
            'valid' => true,
            'discount' => $discount,
            'message' => 'Coupon is valid'
        ];
    }
}

// app/Actions/Grabber/IndexGrabberLogsAction.php
namespace App\Actions\Grabber;

use App\Models\GrabberLog;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexGrabberLogsAction
{
    public function execute(array $filters = []): LengthAwarePaginator
    {
        return GrabberLog::query()
            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['external_id'] ?? null, fn($q, $id) => $q->where('external_id', $id))
            ->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }
}

// app/Actions/Collections/CreateCollectionAction.php
namespace App\Actions\Collections;

use App\DTOs\CollectionDTO;
use Lunar\Models\Collection;
use Illuminate\Support\Str;

class CreateCollectionAction
{
    public function execute(CollectionDTO $dto): Collection
    {
        return Collection::create([
            'name' => $dto->name,
            'slug' => $dto->slug ?: Str::slug($dto->name),
            'description' => $dto->description,
            'parent_id' => $dto->parent_id
        ]);
    }
}

// app/Actions/Collections/UpdateCollectionAction.php
namespace App\Actions\Collections;

use App\DTOs\CollectionDTO;
use Lunar\Models\Collection;
use Illuminate\Support\Str;

class UpdateCollectionAction
{
    public function execute(Collection $collection, CollectionDTO $dto): Collection
    {
        $collection->update([
            'name' => $dto->name ?? $collection->name,
            'slug' => $dto->slug ?? ($dto->name ? Str::slug($dto->name) : $collection->slug),
            'description' => $dto->description ?? $collection->description,
            'parent_id' => $dto->parent_id ?? $collection->parent_id
        ]);

        return $collection;
    }
}

// app/Actions/Cart/CreateCartAction.php
namespace App\Actions\Cart;

use App\DTOs\CartDTO;
use Lunar\Models\Cart;
use Ramsey\Uuid\Uuid;

class CreateCartAction
{
    public function execute(CartDTO $dto): Cart
    {
        return Cart::create([
            'session_id' => $dto->session_id ?: Uuid::uuid4()->toString(),
            'user_id' => $dto->user_id,
            'meta' => $dto->meta
        ]);
    }
}

// app/Actions/Cart/AddItemToCartAction.php
namespace App\Actions\Cart;

use App\DTOs\CartItemDTO;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;

class AddItemToCartAction
{
    public function execute(Cart $cart, CartItemDTO $dto): CartLine
    {
        // Check if item already exists in cart
        $existingLine = $cart->lines()
            ->where('purchasable_id', $dto->purchasable_id)
            ->where('purchasable_type', $dto->purchasable_type)
            ->first();

        if ($existingLine) {
            $existingLine->update([
                'quantity' => $existingLine->quantity + $dto->quantity
            ]);
            return $existingLine;
        }

        return CartLine::create([
            'cart_id' => $cart->id,
            'purchasable_id' => $dto->purchasable_id,
            'purchasable_type' => $dto->purchasable_type,
            'quantity' => $dto->quantity,
            'meta' => $dto->meta
        ]);
    }
}

// app/Actions/Grabber/ProcessGrabberDataAction.php
namespace App\Actions\Grabber;

use App\Models\GrabberLog;
use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Actions\Brands\CreateBrandAction;
use App\Actions\Brands\UpdateBrandAction;
use App\Actions\Collections\CreateCollectionAction;
use App\Actions\Collections\UpdateCollectionAction;
use App\DTOs\ProductDTO;
use App\DTOs\BrandDTO;
use App\DTOs\CollectionDTO;
use Lunar\Models\Product;
use Lunar\Models\Brand;
use Lunar\Models\Collection;
use Illuminate\Support\Facades\DB;

class ProcessGrabberDataAction
{
    public function execute(string $type, string $action, string $externalId, array $data): array
    {
        $log = GrabberLog::create([
            'external_id' => $externalId,
            'type' => $type,
            'action' => $action,
            'payload' => $data,
            'status' => 'processing'
        ]);

        try {
            DB::beginTransaction();

            $result = match ($type) {
                'product' => $this->processProduct($action, $externalId, $data),
                'brand' => $this->processBrand($action, $externalId, $data),
                'collection' => $this->processCollection($action, $externalId, $data),
                default => throw new \InvalidArgumentException('Invalid type')
            };

            $log->update([
                'status' => 'success'
            ]);

            DB::commit();

            return [
                'success' => true,
                'data' => $result,
                'log_id' => $log->id
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'log_id' => $log->id
            ];
        }
    }

    private function processProduct(string $action, string $externalId, array $data)
    {
        $dto = ProductDTO::fromRequest($data);
        
        if ($action === 'create') {
            return (new CreateProductAction())->execute($dto);
        }

        // For update, find by external_id or create if not exists
        $product = Product::where('attribute_data->external_id', $externalId)->first();
        
        if (!$product && $action === 'update') {
            return (new CreateProductAction())->execute($dto);
        }

        return (new UpdateProductAction())->execute($product, $dto);
    }

    private function processBrand(string $action, string $externalId, array $data)
    {
        $dto = BrandDTO::fromRequest($data);
        
        if ($action === 'create') {
            return (new CreateBrandAction())->execute($dto);
        }

        $brand = Brand::where('attribute_data->external_id', $externalId)->first();
        
        if (!$brand && $action === 'update') {
            return (new CreateBrandAction())->execute($dto);
        }

        return (new UpdateBrandAction())->execute($brand, $dto);
    }

    private function processCollection(string $action, string $externalId, array $data)
    {
        $dto = CollectionDTO::fromRequest($data);
        
        if ($action === 'create') {
            return (new CreateCollectionAction())->execute($dto);
        }

        $collection = Collection::where('attribute_data->external_id', $externalId)->first();
        
        if (!$collection && $action === 'update') {
            return (new CreateCollectionAction())->execute($dto);
        }

        return (new UpdateCollectionAction())->execute($collection, $dto);
    }
}

// =============================================================================
// RESOURCES
// =============================================================================

// app/Http/Resources/ProductResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'attribute_data' => $this->attribute_data,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'collections' => CollectionResource::collection($this->whenLoaded('collections')),
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

// app/Http/Resources/ProductVariantResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'attribute_data' => $this->attribute_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

// app/Http/Resources/BrandResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'products_count' => $this->when(isset($this->products_count), $this->products_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

// app/Http/Resources/CollectionResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'parent' => new CollectionResource($this->whenLoaded('parent')),
            'children' => CollectionResource::collection($this->whenLoaded('children')),
            'products_count' => $this->when(isset($this->products_count), $this->products_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

// app/Http/Resources/CartResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'user_id' => $this->user_id,
            'total' => $this->total,
            'sub_total' => $this->sub_total,
            'tax_total' => $this->tax_total,
            'discount_total' => $this->discount_total,
            'lines' => CartLineResource::collection($this->whenLoaded('lines')),
            'discounts' => DiscountResource::collection($this->whenLoaded('discounts')),
            'meta' => $this->meta,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

// app/Http/Resources/CartLineResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartLineResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'purchasable' => $this->whenLoaded('purchasable', function () {
                return match ($this->purchasable_type) {
                    'Lunar\Models\ProductVariant' => new ProductVariantResource($this->purchasable),
                    default => $this->purchasable
                };
            }),
            'meta' => $this->meta,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

// app/Http/Resources/DiscountResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'handle' => $this->handle,
            'coupon' => $this->coupon,
            'type' => $this->type,
            'discount_amount' => $this->discount_amount,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

// =============================================================================
// FILTERS
// =============================================================================

// app/Http/Filters/ProductFilter.php
namespace App\Http\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return match ($property) {
            'brand' => $query->whereHas('brand', fn($q) => $q->where('slug', $value)),
            'collection' => $query->whereHas('collections', fn($q) => $q->where('slug', $value)),
            'status' => $query->where('status', $value),
            'price_min' => $query->whereHas('variants', fn($q) => $q->where('price', '>=', $value * 100)),
            'price_max' => $query->whereHas('variants', fn($q) => $q->where('price', '<=', $value * 100)),
            default => $query
        };
    }
}

// app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\DTOs\ProductDTO;
use App\Actions\Products\IndexProductsAction;
use App\Actions\Products\ShowProductAction;
use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Actions\Products\DeleteProductAction;
use Lunar\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(IndexProductsAction $action): JsonResponse
    {
        $products = $action->execute(['per_page' => request('per_page', 15)]);

        return response()->json([
            'data' => ProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ]);
    }

    public function show(Product $product, ShowProductAction $action): JsonResponse
    {
        $product = $action->execute($product);
        return response()->json(['data' => new ProductResource($product)]);
    }

    public function store(ProductRequest $request, CreateProductAction $action): JsonResponse
    {
        $dto = ProductDTO::fromRequest($request->validated());
        $product = $action->execute($dto);
        
        return response()->json([
            'data' => new ProductResource($product),
            'message' => 'Product created successfully'
        ], 201);
    }

    public function update(ProductRequest $request, Product $product, UpdateProductAction $action): JsonResponse
    {
        $dto = ProductDTO::fromRequest($request->validated());
        $product = $action->execute($product, $dto);
        
        return response()->json([
            'data' => new ProductResource($product),
            'message' => 'Product updated successfully'
        ]);
    }

    public function destroy(Product $product, DeleteProductAction $action): JsonResponse
    {
        $action->execute($product);
        
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}

// app/Http/Controllers/Api/BrandController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\DTOs\BrandDTO;
use App\Actions\Brands\IndexBrandsAction;
use App\Actions\Brands\ShowBrandAction;
use App\Actions\Brands\CreateBrandAction;
use App\Actions\Brands\UpdateBrandAction;
use App\Actions\Brands\DeleteBrandAction;
use Lunar\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function index(IndexBrandsAction $action): JsonResponse
    {
        $brands = $action->execute(['per_page' => request('per_page', 15)]);
        
        return response()->json([
            'data' => BrandResource::collection($brands->items()),
            'meta' => [
                'current_page' => $brands->currentPage(),
                'last_page' => $brands->lastPage(),
                'per_page' => $brands->perPage(),
                'total' => $brands->total()
            ]
        ]);
    }

    public function show(Brand $brand, ShowBrandAction $action): JsonResponse
    {
        $brand = $action->execute($brand);
        return response()->json(['data' => new BrandResource($brand)]);
    }

    public function store(BrandRequest $request, CreateBrandAction $action): JsonResponse
    {
        $dto = BrandDTO::fromRequest($request->validated());
        $brand = $action->execute($dto);
        
        return response()->json([
            'data' => new BrandResource($brand),
            'message' => 'Brand created successfully'
        ], 201);
    }

    public function update(BrandRequest $request, Brand $brand, UpdateBrandAction $action): JsonResponse
    {
        $dto = BrandDTO::fromRequest($request->validated());
        $brand = $action->execute($brand, $dto);
        
        return response()->json([
            'data' => new BrandResource($brand),
            'message' => 'Brand updated successfully'
        ]);
    }

    public function destroy(Brand $brand, DeleteBrandAction $action): JsonResponse
    {
        $action->execute($brand);
        
        return response()->json([
            'message' => 'Brand deleted successfully'
        ]);
    }
}

// app/Http/Controllers/Api/CollectionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionRequest;
use App\Http\Resources\CollectionResource;
use App\DTOs\CollectionDTO;
use App\Actions\Collections\CreateCollectionAction;
use App\Actions\Collections\UpdateCollectionAction;
use Lunar\Models\Collection;
use Illuminate\Http\JsonResponse;

class CollectionController extends Controller
{
    public function index(): JsonResponse
    {
        $collections = Collection::with(['parent', 'children'])
            ->withCount('products')
            ->paginate(15);
        
        return response()->json([
            'data' => CollectionResource::collection($collections->items()),
            'meta' => [
                'current_page' => $collections->currentPage(),
                'last_page' => $collections->lastPage(),
                'per_page' => $collections->perPage(),
                'total' => $collections->total()
            ]
        ]);
    }

    public function show(Collection $collection): JsonResponse
    {
        $collection->load(['parent', 'children'])->loadCount('products');
        return response()->json(['data' => new CollectionResource($collection)]);
    }

    public function store(CollectionRequest $request, CreateCollectionAction $action): JsonResponse
    {
        $dto = CollectionDTO::fromRequest($request->validated());
        $collection = $action->execute($dto);
        
        return response()->json([
            'data' => new CollectionResource($collection),
            'message' => 'Collection created successfully'
        ], 201);
    }

    public function update(CollectionRequest $request, Collection $collection, UpdateCollectionAction $action): JsonResponse
    {
        $dto = CollectionDTO::fromRequest($request->validated());
        $collection = $action->execute($collection, $dto);
        
        return response()->json([
            'data' => new CollectionResource($collection),
            'message' => 'Collection updated successfully'
        ]);
    }

    public function destroy(Collection $collection): JsonResponse
    {
        $collection->delete();
        
        return response()->json([
            'message' => 'Collection deleted successfully'
        ]);
    }
}

// app/Http/Controllers/Api/CartController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartItemRequest;
use App\Http\Resources\CartResource;
use App\DTOs\CartDTO;
use App\DTOs\CartItemDTO;
use App\Actions\Cart\CreateCartAction;
use App\Actions\Cart\AddItemToCartAction;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        
        $cart = Cart::where('session_id', $sessionId)
            ->with(['lines.purchasable', 'discounts'])
            ->first();

        if (!$cart) {
            // Create new cart if not exists
            $dto = new CartDTO(session_id: $sessionId);
            $cart = (new CreateCartAction())->execute($dto);
        }

        return response()->json(['data' => new CartResource($cart)]);
    }

    public function store(Request $request, CreateCartAction $action): JsonResponse
    {
        $sessionId = Uuid::uuid4()->toString();
        $dto = new CartDTO(
            session_id: $sessionId,
            user_id: $request->user()?->id
        );
        
        $cart = $action->execute($dto);
        
        return response()->json([
            'data' => new CartResource($cart),
            'session_id' => $sessionId,
            'message' => 'Cart created successfully'
        ], 201);
    }

    public function addItem(CartItemRequest $request, AddItemToCartAction $action): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart) {
            $dto = new CartDTO(session_id: $sessionId);
            $cart = (new CreateCartAction())->execute($dto);
        }

        $dto = CartItemDTO::fromRequest($request->validated());
        $cartLine = $action->execute($cart, $dto);
        
        $cart->load(['lines.purchasable', 'discounts']);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Item added to cart successfully'
        ]);
    }

    public function updateItem(Request $request, CartLine $cartLine): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartLine->update(['quantity' => $request->quantity]);
        $cart = $cartLine->cart->load(['lines.purchasable', 'discounts']);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Cart item updated successfully'
        ]);
    }

    public function removeItem(CartLine $cartLine): JsonResponse
    {
        $cart = $cartLine->cart;
        $cartLine->delete();
        
        $cart->load(['lines.purchasable', 'discounts']);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Item removed from cart successfully'
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if ($cart) {
            $cart->lines()->delete();
            $cart->load(['lines.purchasable', 'discounts']);
        }
        
        return response()->json([
            'data' => $cart ? new CartResource($cart) : null,
            'message' => 'Cart cleared successfully'
        ]);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon' => 'required|string'
        ]);

        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        $cart = Cart::where('session_id', $sessionId)->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        // Apply coupon logic using Lunar's discount system
        try {
            $cart->coupon_code = $request->coupon;
            $cart->save();
            
            $cart->load(['lines.purchasable', 'discounts']);
            
            return response()->json([
                'data' => new CartResource($cart),
                'message' => 'Coupon applied successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid coupon code'
            ], 400);
        }
    }

    public function removeCoupon(Request $request): JsonResponse
    {
        $sessionId = $request->header('X-Cart-Session') ?? $request->session()->getId();
        $cart = Cart::where('session_id', $sessionId)->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $cart->coupon_code = null;
        $cart->save();
        
        $cart->load(['lines.purchasable', 'discounts']);
        
        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Coupon removed successfully'
        ]);
    }
}

// app/Http/Controllers/Api/DiscountController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use Lunar\Models\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(): JsonResponse
    {
        $discounts = Discount::where('starts_at', '<=', now())
            ->where(function($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
            })
            ->paginate(15);
        
        return response()->json([
            'data' => DiscountResource::collection($discounts->items()),
            'meta' => [
                'current_page' => $discounts->currentPage(),
                'last_page' => $discounts->lastPage(),
                'per_page' => $discounts->perPage(),
                'total' => $discounts->total()
            ]
        ]);
    }

    public function show(Discount $discount): JsonResponse
    {
        return response()->json(['data' => new DiscountResource($discount)]);
    }

    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon' => 'required|string'
        ]);

        $discount = Discount::where('coupon', $request->coupon)
            ->where('starts_at', '<=', now())
            ->where(function($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
            })
            ->first();

        if (!$discount) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired coupon code'
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'data' => new DiscountResource($discount),
            'message' => 'Coupon is valid'
        ]);
    }
}

// app/Http/Controllers/Api/GrabberController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GrabberRequest;
use App\Actions\Grabber\ProcessGrabberDataAction;
use App\Models\GrabberLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrabberController extends Controller
{
    public function __construct()
    {
        // Add API key authentication middleware here if needed
        // $this->middleware('auth.api_key');
    }

    public function process(GrabberRequest $request, ProcessGrabberDataAction $action): JsonResponse
    {
        $result = $action->execute(
            type: $request->validated('type'),
            action: $request->validated('action'),
            externalId: $request->validated('external_id'),
            data: $request->validated('data')
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Data processed successfully',
                'log_id' => $result['log_id']
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
            'log_id' => $result['log_id']
        ], 400);
    }

    public function batchProcess(Request $request, ProcessGrabberDataAction $action): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|max:100',
            'items.*.type' => 'required|in:product,brand,collection',
            'items.*.action' => 'required|in:create,update',
            'items.*.external_id' => 'required|string',
            'items.*.data' => 'required|array'
        ]);

        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($request->items as $item) {
            $result = $action->execute(
                type: $item['type'],
                action: $item['action'],
                externalId: $item['external_id'],
                data: $item['data']
            );

            $results[] = [
                'external_id' => $item['external_id'],
                'success' => $result['success'],
                'log_id' => $result['log_id'],
                'error' => $result['error'] ?? null
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return response()->json([
            'success' => $failureCount === 0,
            'processed' => count($results),
            'successful' => $successCount,
            'failed' => $failureCount,
            'results' => $results
        ]);
    }

    public function logs(Request $request): JsonResponse
    {
        $logs = GrabberLog::query()
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->external_id, fn($q) => $q->where('external_id', $request->external_id))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total()
            ]
        ]);
    }
}

// =============================================================================
// ROUTES
// =============================================================================

// routes/api.php
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\GrabberController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    // Products
    Route::apiResource('products', ProductController::class);
    
    // Brands
    Route::apiResource('brands', BrandController::class);
    
    // Collections
    Route::apiResource('collections', CollectionController::class);
    
    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'show']);
        Route::post('/', [CartController::class, 'store']);
        Route::post('/items', [CartController::class, 'addItem']);
        Route::put('/items/{cartLine}', [CartController::class, 'updateItem']);
        Route::delete('/items/{cartLine}', [CartController::class, 'removeItem']);
        Route::delete('/clear', [CartController::class, 'clear']);
        Route::post('/coupon', [CartController::class, 'applyCoupon']);
        Route::delete('/coupon', [CartController::class, 'removeCoupon']);
    });
    
    // Discounts
    Route::get('discounts', [DiscountController::class, 'index']);
    Route::get('discounts/{discount}', [DiscountController::class, 'show']);
    Route::post('discounts/validate-coupon', [DiscountController::class, 'validateCoupon']);
    
    // Grabber (External API)
    Route::prefix('grabber')->group(function () {
        Route::post('/process', [GrabberController::class, 'process']);
        Route::post('/batch-process', [GrabberController::class, 'batchProcess']);
        Route::get('/logs', [GrabberController::class, 'logs']);
    });
});

// =============================================================================
// MIDDLEWARE
// =============================================================================

// app/Http/Middleware/ApiKeyAuthentication.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey || $apiKey !== config('app.grabber_api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}

// =============================================================================
// SERVICE PROVIDER
// =============================================================================

// app/Providers/AppServiceProvider.php - Add to boot method
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lunar\Base\CartSessionInterface;
use App\Services\CartSessionManager;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Custom cart session manager
        $this->app->bind(CartSessionInterface::class, CartSessionManager::class);
    }
}

// app/Services/CartSessionManager.php
namespace App\Services;

use Lunar\Base\CartSessionInterface;
use Lunar\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class CartSessionManager implements CartSessionInterface
{
    public function __construct(
        protected Request $request
    ) {}

    public function getSessionKey(): string
    {
        // Check for custom cart session header first
        $sessionId = $this->request->header('X-Cart-Session');
        
        if (!$sessionId) {
            $sessionId = Session::getId();
            
            if (!$sessionId) {
                $sessionId = Uuid::uuid4()->toString();
                Session::setId($sessionId);
            }
        }
        
        return $sessionId;
    }

    public function current(): ?Cart
    {
        return Cart::where('session_id', $this->getSessionKey())->first();
    }

    public function forget(): void
    {
        $cart = $this->current();
        
        if ($cart) {
            $cart->delete();
        }
    }
}

// =============================================================================
// CONFIGURATION
// =============================================================================

// config/lunar.php - Add these configurations
return [
    // ... existing config
    
    'cart' => [
        'auto_create' => true,
        'session_key' => 'lunar_cart',
        'calculate' => true,
    ],
    
    'pricing' => [
        'stored_in_cents' => true,
    ],
    
    'search' => [
        'engine' => 'collection',
    ],
];

// .env - Add these environment variables
/*
GRABBER_API_KEY=your-secret-api-key-here
LUNAR_CART_SESSION_DRIVER=custom
*/

// =============================================================================
// USAGE EXAMPLES
// =============================================================================

/*
// Example API requests:

// 1. Get products with filters
GET /api/v1/products?filter[brand]=nike&filter[status]=published&sort=name&include=brand,collections

// 2. Create a product
POST /api/v1/products
{
    "name": "Nike Air Max",
    "description": "Comfortable running shoes",
    "brand_id": 1,
    "collection_ids": [1, 2],
    "variants": [
        {
            "sku": "NAM-001-S",
            "price": 12000,
            "attribute_data": {"size": "S"}
        }
    ]
}

// 3. Add item to cart
POST /api/v1/cart/items
Headers: X-Cart-Session: uuid-here
{
    "purchasable_id": 1,
    "purchasable_type": "Lunar\\Models\\ProductVariant",
    "quantity": 2
}

// 4. Apply coupon
POST /api/v1/cart/coupon
Headers: X-Cart-Session: uuid-here
{
    "coupon": "SAVE10"
}

// 5. External API grabber
POST /api/v1/grabber/process
Headers: X-API-Key: your-secret-key
{
    "type": "product",
    "action": "create",
    "external_id": "ext-123",
    "data": {
        "name": "External Product",
        "description": "From external system",
        "variants": [
            {
                "sku": "EXT-001",
                "price": 5000
            }
        ]
    }
}

// 6. Batch process multiple items
POST /api/v1/grabber/batch-process
Headers: X-API-Key: your-secret-key
{
    "items": [
        {
            "type": "brand",
            "action": "create",
            "external_id": "brand-1",
            "data": {"name": "Brand One"}
        },
        {
            "type": "product",
            "action": "create", 
            "external_id": "prod-1",
            "data": {"name": "Product One", "brand_id": 1}
        }
    ]
}
*/