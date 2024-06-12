<x-filament::page>
    <x-filament::card>
        <form wire:submit.prevent="prediction">            {{ $this->form }}
            <div class="flex justify-end mt-2">
                <button type="submit"
                        class="px-3 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded"> Prediksi
                    <div wire:loading> ...
                    </div>
                </button>
            </div>
        </form>
    </x-filament::card>
    @if($display_table_calculation)
        @php
            $strRandom = \Illuminate\Support\Str::random();        @endphp
        <x-filament::card>
            <livewire:calculation-table :allMounths="$allMounths"
                                        :locationID="$locationID" :yearID="$yearID"
                                        :key="$strRandom"/>
        </x-filament::card>
    @endif
</x-filament::page>
