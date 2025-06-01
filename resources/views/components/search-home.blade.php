<div class="container px-4">
                        <!-- Search Bar -->
                        <form class="search-form mb-6" action="/search" method="GET">
                            <div class="flex flex-col lg:flex-row gap-4">
                                <div class="flex-1">
                                    <input type="text" name="destination" value="{{ request('destination') }}"
                                        placeholder="Destinazione" class="input input-bordered w-full">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:w-96">
                                    <input type="date" name="checkin" value="{{ request('checkin') }}"
                                        class="input input-bordered">
                                    <input type="date" name="checkout" value="{{ request('checkout') }}"
                                        class="input input-bordered">
                                    <select name="guests" class="select select-bordered">
                                        <option value="">Ospiti</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('guests') == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'ospite' : 'ospiti' }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Cerca
                                </button>
                            </div>
                        </form>
                    </div>