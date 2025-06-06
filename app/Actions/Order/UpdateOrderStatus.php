<?php

declare(strict_types=1);

namespace App\Actions\Order;

use Lunar\Models\Order;

class UpdateOrderStatus
{
    public function execute(Order $order, string $status, ?string $notes = null): Order
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];

        if (! in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $order->update([
            'status' => $status,
            'notes' => $notes ? ($order->notes."\n".$notes) : $order->notes,
        ]);

        // Add status change to order history/timeline if you have that feature
        // $order->timeline()->create([...]);

        return $order->fresh();
    }
}
