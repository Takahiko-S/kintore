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
                @endforeach

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary mt-5 w-25">更新</button>
                </div>
            </form>
        </div>

        <div class="col-10 mx-auto mt-5">

        </div>
    </x-slot>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                $(document).on('click', '.add-menu', function() {
                    var row = $(this).closest('.menu-row');
                    var newRowIndex = $('.menu-row').length; // Get the new index before cloning
                    var newRow = row.clone();

                    // Update the name attribute with the new index
                    newRow.find('input[name^="menu_exercises"]').each(function() {
                        var newName = this.name.replace(/\[\d+\]/, '[' + newRowIndex + ']');
                        this.name = newName;
                    });

                    var exerciseName = newRow.find('input[name^="menu_exercises"][name$="[name]"]');
                    exerciseName.after('<input type="hidden" name="' + exerciseName.attr('name') + '" value="' +
                        exerciseName.val() + '">'); // Add hidden field with exercise name
                    exerciseName.prev('label').hide(); // Hide the label
                    exerciseName.prop('disabled', true).hide(); // Hide the exercise name field

                    newRow.find(
                        'input[name^="menu_exercises"][name$="[planned_sets]"], input[name^="menu_exercises"][name$="[weight]"]'
                    ).val(''); // Reset set and weight inputs

                    newRow.insertAfter(row); // Insert new row after the current one
                });
            });
        </script>

    </x-slot>

</x-base-layout>
