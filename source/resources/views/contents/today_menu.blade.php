<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">

        </style>
    </x-slot>

    <x-slot name="main">
        <div class="container">
            <h1>{{ \Carbon\Carbon::now()->format('Y/m/d') }}: {{ $menu->name }}</h1>
            <p>{{ $menu->description }}</p>

            <h2>Exercises</h2>
            @foreach ($menu->menuExercises->groupBy('exercise_id') as $exercise_id => $menuExercises)
                <div style="background-color: {{ $loop->iteration % 2 == 0 ? '#f8f9fa' : '#e9ecef' }};">
                    <h3>{{ $menuExercises->first()->exercise->name }}</h3>
                    <table class="table">
                        <thead>

                            <tr class="text-center">

                                <th scope="col">セット</th>
                                <th scope="col">Reps</th>
                                <th scope="col">Weight (kg)</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($menuExercises as $index => $menuExercise)

                                <tr class="text-center">

                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $menuExercise->reps }}</td>
                                    <td>{{ $menuExercise->weight }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
            <div class="col-10 mx-auto mt-5 ">
                <a href="{{ route('today_edit', ['id' => $menu->id]) }}" class="btn btn-primary btn-lg w-100">種目の変更</a>
            </div>
        </div>


    </x-slot>

    <x-slot name="script"></x-slot>

</x-base-layout>
