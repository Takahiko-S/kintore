<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">

        </style>
    </x-slot>

 <x-slot name="main">
 <div class="container">
        <h1>メニューを編集: {{ $today->name }}</h1>
        
       <form action="{{ route('today.update', ['today' => $today->id]) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>メニュー名</label>
                <input type="text" name="name" value="{{ $today->name }}" class="form-control">
            </div>

            <h2>種目</h2>
            @foreach ($today->menuExercises as $index => $menuExercise)
                <div class="form-group">
                    <label>種目名</label>
                    <input type="text" name="menu_exercises[{{ $index }}][name]" value="{{ $menuExercise->exercise->name }}" class="form-control">

                    <label>セット数</label>
                    <input type="number" name="menu_exercises[{{ $index }}][planned_sets]" value="{{ $menuExercise->planned_sets }}" class="form-control">

                    <label>重量 (kg)</label>
                    <input type="number" name="menu_exercises[{{ $index }}][weight]" value="{{ $menuExercise->weight }}" class="form-control">
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">更新</button>
        </form>
    </div>

      <div class="col-10 mx-auto mt-5">
                    
                </div>
</x-slot>

        <x-slot name="script"></x-slot>

</x-base-layout>
