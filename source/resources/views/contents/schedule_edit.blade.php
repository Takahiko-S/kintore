<x-base-layout>

    <x-slot name="title">スケジュール編集</x-slot>
    <x-slot name="css">
        <style>
            /* 必要なCSSをここに書く */
        </style>
        <x-slot name="main">
            <div class="container">
                <h1>スケジュール編集</h1>

                <form action="{{ route('schedule/{id}', ['today' => $today->id]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>メニュー名</label>
                        <input type="text" name="name" value="{{ $today->name }}" class="form-control">
                    </div>

                    <h2>種目</h2>
                    <div class="form-group row menu-row">
                        <!-- Here we moved menu-row class -->
                        <div class="col-md-12">
                            <label>種目名</label>
                            <input type="text" name="menu_exercises[{{ $index }}][name]"
                                value="{{ $menuExercise->exercise->name }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>セット数</label>
                            <input type="number" name="menu_exercises[{{ $index }}][planned_sets]"
                                value="{{ $menuExercise->planned_sets }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>重量 (kg)</label>
                            <input type="number" name="menu_exercises[{{ $index }}][weight]"
                                value="{{ $menuExercise->weight }}" class="form-control">
                        </div>
                        <div class="col-md-4 align-items-end d-flex justify-content-between">
                            <button type="button" class="btn btn-primary w-100 add-menu">メニュー追加</button>
                            <form action="{{ route('today_destroy', $menuExercise->id) }}" method="POST"
                                class="w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">削除</button>
                            </form>
                        </div>
                    </div>


                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary mt-5 w-25">更新</button>
                    </div>
                </form>
            </div>
        </x-slot>
        <x-slot name="script">
            <script></script>
        </x-slot>
    </x-slot>
</x-base-layout>
