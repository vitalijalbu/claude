<article class="group flex w-full flex-col overflow-hidden border rounded-xl border-outline bg-white hover:shadow-sm">
<a href="{{ route('city.show', ['city' => $city->slug]) }}"
                class="p-4 flex items-center gap-2">
                <x-icon name="point-love" />
                <h5 class="font-semibold text-gray-900 dark:text-white truncate">{{ $city->name }}</h5>
            </a>
</article>