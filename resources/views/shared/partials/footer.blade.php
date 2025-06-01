            @php
                $footerLinks = [
                    __('footer_col_1') => [
                        ['name' => 'Solutions', 'url' => '#'],
                        ['name' => 'Features', 'url' => '#'],
                        ['name' => 'Pricing Plans', 'url' => '#'],
                        ['name' => 'Analytics', 'url' => '#'],
                        ['name' => 'Support Center', 'url' => '#'],
                    ],
                    __('footer_col_2') => [
                        ['name' => 'Team', 'url' => '#'],
                        ['name' => 'Terms of Service', 'url' => '#'],
                        ['name' => 'Privacy Policy', 'url' => '#'],
                        ['name' => 'Cookies', 'url' => '#'],
                        ['name' => 'Refunds', 'url' => '#'],
                    ],
                    __('footer_col_3') => [
                        ['name' => 'Team', 'url' => '#'],
                        ['name' => 'Terms of Service', 'url' => '#'],
                        ['name' => 'Privacy Policy', 'url' => '#'],
                        ['name' => 'Cookies', 'url' => '#'],
                        ['name' => 'Refunds', 'url' => '#'],
                    ],
                ];
            @endphp

            <footer id="site-footer" class="bg-zinc-800 dark:text-gray-100">
                <div class="container py-16">
                    <div class="grid grid-cols-1 gap-12 md:grid-cols-5 md:gap-4 lg:gap-10">
                        <div class="text-white">
                            <a href="/">ALBALUXSTAY</a>
                        </div>
                        @foreach ($footerLinks as $category => $links)
                            <div class="space-y-6">
                                <h4
                                    class="text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-400/75">
                                    {{ $category }}
                                </h4>
                                <nav class="flex flex-col gap-3 text-sm">
                                    @foreach ($links as $link)
                                        <a href="{{ $link['url'] }}"
                                            class="font-medium text-gray-100 hover:text-gray-950 dark:text-gray-400 dark:hover:text-gray-50">
                                            {{ $link['name'] }}
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        @endforeach

                        <div class="space-y-6">
                            <h4
                                class="text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-400/75">
                                ALBALUXSTAY {{ date('Y') }}
                            </h4>
                        </div>
                    </div>

                    <hr class="my-10 border-gray-500 dark:border-gray-700/75" />

                    <div
                        class="flex flex-col gap-4 text-center text-sm md:flex-row-reverse md:justify-between md:gap-0 md:text-left">
                        <nav class="space-x-4">
                            <a href="#"
                                class="text-gray-400 hover:text-gray-800 dark:hover:text-white">Twitter</a>
                            <a href="#" class="text-gray-400 hover:text-[#1877f2]">Facebook</a>
                            <a href="#" class="text-gray-400 hover:text-[#405de6]">Instagram</a>
                        </nav>
                    </div>
                </div>
            </footer>
