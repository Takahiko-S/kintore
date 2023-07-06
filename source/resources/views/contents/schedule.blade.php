<x-base-layout>
    <x-slot name="title">筋トレスケジュール</x-slot>

    <x-slot name="css">
        <style>
            /* 必要なCSSをここに書く */
        </style>
    </x-slot>

    <x-slot name="main">
        <div class="container">
            <h2 class="mt-4">筋トレスケジュール</h2>

             <form action="{{ route('schedule.store') }}" method="POST" class="mt-4">
            @csrf

            @for ($menuIndex = 1; $menuIndex <= 7; $menuIndex++)
                <h4 class="mt-3">メニュー {{ $menuIndex }}</h4>
                @for ($workoutIndex = 1; $workoutIndex <= 15; $workoutIndex++)
                    <div class="mb-3">
                        <label for="menu-{{ $menuIndex }}-workout-{{ $workoutIndex }}" class="form-label">種目 {{ $workoutIndex }}</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" style="width: 100%;" id="menu-{{ $menuIndex }}-workout-{{ $workoutIndex }}" name="menus[{{ $menuIndex }}][exercises][{{ $workoutIndex }}][name]" placeholder="種目名を入力">
                            </div>
                            <div class="col-3">
                                <input type="text" style="width: 100%;" id="menu-{{ $menuIndex }}-workout-{{ $workoutIndex }}-weight" name="menus[{{ $menuIndex }}][exercises][{{ $workoutIndex }}][weight]" placeholder="重量を入力">
                            </div>
                            <div class="col-3">
                                <input type="text" style="width: 100%;" id="menu-{{ $menuIndex }}-workout-{{ $workoutIndex }}-reps" name="menus[{{ $menuIndex }}][exercises][{{ $workoutIndex }}][reps]" placeholder="回数を入力">
                            </div>
                        </div>
                    </div>
                @endfor
            @endfor

            <button type="submit" class="btn btn-primary">スケジュールを保存</button>
        </form>
        </div>
    </x-slot>
    <x-slot name="script">
    <script></script>
    
    </x-slot>
</x-base-layout>

