<x-base-layout>

    <x-slot name="title">スケジュール編集</x-slot>
    <x-slot name="css">
        <style>
            /* 必要なCSSをここに書く */
        </style>
        <x-slot name="main">
            <div class="container">
                <h1>スケジュール編集</h1>

                <form action="{{ route('schedule.update', ['id' => $menu->id]) }}" method="post">

                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>メニュー名</label>
                        <input type="text" name="name" value="{{ $menu->name }}" class="form-control">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary mt-5 w-25">更新</button>
                        </div>

                    </div>


                    @foreach ($menu->menuExercises as $index => $menuExercise)
                        <div class="exercise-container">
                            <!-- Here we moved menu-row class -->
                            <div class="col-md-12">
                                <label>種目名</label>
                                <input type="text" name="menu_exercises[{{ $index }}][name]"
                                    value="{{ $menuExercise->exercise->name }}" class="form-control">
                            </div>
                            <div class="form-group row menu-row">
                                <div class="col-md-4">
                                    <label>回数</label>
                                    <input type="number" name="menu_exercises[{{ $index }}][reps]"
                                        value="{{ $menuExercise->reps }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>重量 (kg)</label>
                                    <input type="number" name="menu_exercises[{{ $index }}][weight]"
                                        value="{{ $menuExercise->weight }}" class="form-control">
                                </div>
                                <div class="col-md-4 align-items-end d-flex">
                                    <div class="w-50 pr-1">
                                        <button type="button" class="btn btn-primary w-100">メニュー追加</button>
                                    </div>
                                    <div class="w-50 pl-1">
                                        <form action="{{ route('today_destroy', $menu->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">削除</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </form>
            </div>
        </x-slot>
        <x-slot name="script">
            <script>
                $(document).ready(function() {
                    var index = $('input[name^="menu_exercises"]').length; // get the current number of menu exercises

                    $(document).on('click', '.btn-primary', function(e) {
                        e.preventDefault();

                        // Clone the menu row
                        var newExercise = $(this).closest('.menu-row').clone();

                        // Remove the name input
                        newExercise.find('.col-md-12').remove();

                        // Update the name attributes with the new index
                        newExercise.find('input[name^="menu_exercises"]').each(function() {
                            var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                            $(this).attr('name', name);
                        });

                        // Clear the input values
                        newExercise.find('input[type="number"]').val('');

                        // Remove the "add" button from the new row
                        newExercise.find('.btn-primary').remove();

                        // Append the new menu item at the end of the current exercise
                        $(this).closest('.exercise-container').append(newExercise);

                        // Increment the index for next addition
                        index++;
                    });

                    // Event delegation to handle dynamically added elements
                    $(document).on('click', '.btn-danger', function(e) {
                        e.preventDefault();
                        $(this).closest('.menu-row').remove();
                    });
                });
            </script>

        </x-slot>
    </x-slot>
</x-base-layout>
