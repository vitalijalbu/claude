<article class="group flex w-full flex-col overflow-hidden border rounded-xl border-outline bg-white">
    <a href="#">
        <div class="h-44 md:h-50 overflow-hidden ">
            <img src="/images/placeholder.svg"
                class="object-cover transition duration-700 h-full w-full ease-out group-hover:scale-105"
                alt="{{ $data->title }}" />
        </div>
        <div class="flex flex-col gap-4 p-6">
            <h3 class="text-balance text-xl lg:text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong"
                aria-describedby="categoryDescription">{{ $data->title }}</h3>
            @if($data->description)
                <p id="categoryDescription" class="text-pretty text-sm">{{ $data->description }}</p>
            @endif
        </div>
    </a>
</article>