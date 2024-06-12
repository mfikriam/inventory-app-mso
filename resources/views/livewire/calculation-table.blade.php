@php
    // Setel locale ke bahasa yang diinginkan
    \Carbon\Carbon::setLocale('id');
@endphp

<div class="w-full overflow-x-auto">
    <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">
                Periode
            </th>

            <th scope="col" class="px-6 py-3">
                Past Due
            </th>

            @foreach($allMounths as $allMounth)
                <th scope="col" class="px-6 py-3">
                    {{ \Carbon\Carbon::createFromFormat('m', trim($allMounth))->format('M') }}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                Gross requrements (GR)
            </th>
            <td class="px-6 py-4"></td>
            @foreach($exitItemCountByMonth as $count)
                <td class="px-6 py-4">
                    {{ $count }}
                </td>
            @endforeach
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                Scheduled Recelpts (SR)
            </th>
            <td class="px-6 py-4"></td>
            @foreach($exitItemCountByMonth as $count)
                <td class="px-6 py-4">
                    0
                </td>
            @endforeach
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            @php
                $mount_count = 0;
                $keys = $exitItemCountByMonth->keys()->all();
            @endphp
            @foreach ($exitItemCountByMonth as $count)
                @php
                    $mount_count += $count;
                @endphp
            @endforeach
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                Projected On-hand (POH)
            </th>
            <td class="px-6 py-4">10</td>
            @foreach($exitItemCountByMonth as $key => $count)
                <td class="px-6 py-4">
                    @php
                        $value = ($count + 28) - 10;
                        $currentIndex = array_search($key, $keys);

                        if ($currentIndex > 0) {
                            $prevKey = $keys[$currentIndex - 1];
                        } else {
                            $prevKey = end($keys);
                        }
                    @endphp
                    @if($value > 28)
                        @if($key != "01")
                            {{  $exitItemCountByMonth[$prevKey] - $count + ((($count + 28) - 10) + 7) }}
                        @else
                            {{ $count - 10 + ((($count + 28) - 10) + 7) }}
                        @endif
                    @else
                        @if($key != "01")
                            {{  $exitItemCountByMonth[$prevKey] - $count }}
                        @else
                            {{ $count - 10 }}
                        @endif
                    @endif
                </td>
            @endforeach
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                Net Requirements (NR)
            </th>
            <td class="px-6 py-4"></td>
            @foreach($exitItemCountByMonth as $count)
                <td class="px-6 py-4">
                    {{ ($count + 28) - 10 }}
                </td>
            @endforeach
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            @php
                $mount_count = 0;
            @endphp
            @foreach ($exitItemCountByMonth as $count)
                @php
                    $mount_count += $count;
                @endphp
            @endforeach
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                Planned Order Receipts
            </th>
            <td class="px-6 py-4"></td>
            @foreach($exitItemCountByMonth as $count)
                <td class="px-6 py-4">
                    {{ (($count + 28) - 10) + 7 }}
                </td>
            @endforeach
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            @php
                $mount_count = 0;
                $keys_2 = $exitItemCountByMonth->keys()->all();
            @endphp
            @foreach ($exitItemCountByMonth as $count)
                @php
                    $mount_count += $count;
                @endphp
            @endforeach
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                Planned Order Releases
            </th>
            <td class="px-6 py-4"></td>
            @foreach($exitItemCountByMonth as $key_2 => $count)
                @php
                    $currentIndex_2 = array_search($key_2, $keys_2);

                    if ($currentIndex_2 == 11) {
                        $afterKey_2 = end($keys_2);
                    } else {
                        $afterKey_2 = $keys_2[$currentIndex_2 + 1];
                    }
                @endphp
                <td class="px-6 py-4">
                    {{ (($exitItemCountByMonth[$afterKey_2] + 28) - 10) + 7 }}
                </td>
            @endforeach
        </tr>
        </tbody>
    </table>
</div>
