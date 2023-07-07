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

            @foreach ($menus as $menuIndex => $menu)
                 <h4 class="mt-3">メニュー {{ $menuIndex + 1 }}</h4>
    @if ($menu->menuExercises)
        @foreach ($menu->menuExercises as $workoutIndex => $menuExercise)
            <div class="mb-3">
                <label for="menu-{{ $menuIndex + 1 }}-workout-{{ $workoutIndex + 1 }}" class="form-label">種目 {{ $workoutIndex + 1 }}</label>
                <div class="row">
                    <div class="col-6">
                        <input type="text" style="width: 100%;" id="menu-{{ $menuIndex + 1 }}-workout-{{ $workoutIndex + 1 }}" value="{{ $menuExercise->exercise->name }}" disabled>
                    </div>
                    <div class="col-3">
                        <input type="text" style="width: 100%;" id="menu-{{ $menuIndex + 1 }}-workout-{{ $workoutIndex + 1 }}-weight" value="{{ $menuExercise->weight }}" disabled>
                    </div>
                    <div class="col-3">
                        <input type="text" style="width: 100%;" id="menu-{{ $menuIndex + 1 }}-workout-{{ $workoutIndex + 1 }}-reps" value="{{ $menuExercise->planned_sets }}" disabled>
                    </div>
                </div>
            </div>
        @endforeach
         @else
        <p>このメニューには内容が登録されていません。</p>
    @endif
            @endforeach
        </div>
    </x-slot>

    <x-slot name="script">
        <script></script>
    </x-slot>
</x-base-layout>


