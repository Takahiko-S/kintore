<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">

        </style>
    </x-slot>

 <x-slot name="main">
    <div class="container">
        <h1>Today's Menu: {{ $menu->name }}</h1>
        <p>{{ $menu->description }}</p>
        
        <h2>Exercises</h2>
        @foreach ($menu->menuExercises as $menuExercise)
            <div style="background-color: {{ $loop->iteration % 2 == 0 ? '#f8f9fa' : '#e9ecef' }};">
                <h3>{{ $menuExercise->exercise->name }}</h3>
                <p>{{ $menuExercise->planned_sets }} sets - {{ $menuExercise->weight }} kg</p>
            </div>
        @endforeach
    </div>
</x-slot>

        <x-slot name="script"></x-slot>

</x-base-layout>
