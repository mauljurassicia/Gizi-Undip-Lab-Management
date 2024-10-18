<div x-data="calendar()" class="calendar mt-4">
    <header class="calendar-header">
        <button @click="prevMonth">Previous</button>
        <h2 x-text="monthName"></h2>
        <button @click="nextMonth">Next</button>
    </header>
    <div class="calendar-grid">
        <template x-for="day in daysOfWeek" :key="day">
            <div class="day-header" x-text="day"></div>
        </template>
        <template x-for="day in daysInMonth" :key="day.date._d" >
            <div class="day" x-text="day.day" :class="{ 'other-month': day.otherMonth }"></div>
        </template>
    </div>
</div>
<script>
    // script.js
// script.js
function calendar() {
    return {
        currentDate: moment(), // Initialize with the current date
        daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        monthName: '', // Track monthName reactively
        daysInMonth: [], // Track daysInMonth reactively

        init() {
            // Initialize the calendar by setting monthName and daysInMonth
            this.updateCalendar();
        },

        updateCalendar() {
            // Update monthName and daysInMonth reactively when currentDate changes
            this.monthName = this.currentDate.format('MMMM YYYY');
            const startOfMonth = this.currentDate.clone().startOf('month');
            const endOfMonth = this.currentDate.clone().endOf('month');
            const days = [];

            // Fill in the previous month's days
            const firstDayOfWeek = startOfMonth.clone().startOf('week');
            for (let day = firstDayOfWeek; day.isBefore(startOfMonth); day.add(1, 'days')) {
                days.push({ day: day.date(), date: day.clone(), otherMonth: true });
            }

            // Fill in the current month's days
            for (let day = startOfMonth; day.isBefore(endOfMonth.clone()); day.add(1, 'days')) {
                days.push({ day: day.date(), date: day.clone(), otherMonth: false });
            }

            // Fill in the next month's days
            const lastDayOfWeek = endOfMonth.clone().endOf('week');
            for (let day = endOfMonth.clone().add(1, 'days'); day.isBefore(lastDayOfWeek.clone().add(1, 'days')); day.add(1, 'days')) {
                days.push({ day: day.date(), date: day.clone(), otherMonth: true });
            }

            this.daysInMonth = days;
        },

        prevMonth() {
            this.currentDate.subtract(1, 'months'); // Go to the previous month
            this.updateCalendar(); // Recalculate monthName and daysInMonth
        },

        nextMonth() {
            this.currentDate.add(1, 'months'); // Go to the next month
            this.updateCalendar(); // Recalculate monthName and daysInMonth
        }
    };
}



</script>
<style>
    .calendar {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: calc(100% - 40px);
    padding: 20px;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.day-header, .day {
    padding: 10px;
    text-align: center;
    border: 1px solid #e0e0e0;
}

.other-month {
    background: #f9f9f9;
}
</style>

@section('scripts')
    <!-- Relational Form table -->
    <script src="https://unpkg.com/alpinejs"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });
            var editor_config = {
                path_absolute: "/",
                selector: 'textarea.my-editor2',
                height: "250",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                menubar: false,
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback: function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document
                        .getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document
                        .getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'filemanager?field_name=' + field_name;
                    cmsURL = cmsURL + "&type=Files";

                    tinyMCE.activeEditor.windowManager.open({
                        file: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no"
                    });
                }
            }
            tinymce.init(editor_config);
        });
        $('.btn-add-related').on('click', function() {
            var relation = $(this).data('relation');
            var index = $(this).parents('.panel').find('tbody tr').length - 1;

            if ($('.empty-data').length) {
                $('.empty-data').hide();
            }

            // TODO: edit these related input fields (input type, option and default value)
            var inputForm = '';
            var fields = $(this).data('fields').split(',');
            // $.each(fields, function(idx, field) {
            //     inputForm += `
        //         <td class="form-group">
        //             {!! Form::select('`+relation+`[`+relation+index+`][`+field+`]', [], null, [
            'class' => 'form-control select2',
            'style' => 'width:100%',
        ]) !!}
        //         </td>
        //     `;
            // })
            $.each(fields, function(idx, field) {
                inputForm += `
                <td class="form-group">
                    {!! Form::text('`+relation+`[`+relation+index+`][`+field+`]', null, [
                        'class' => 'form-control',
                        'style' => 'width:100%',
                    ]) !!}
                </td>
            `;
            })

            var relatedForm = `
            <tr id="` + relation + index + `">
                ` + inputForm + `
                <td class="form-group" style="text-align:right">
                    <button type="button" class="btn-delete btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></button>
                </td>
            </tr>
        `;

            $(this).parents('.panel').find('tbody').append(relatedForm);

            $('#' + relation + index + ' .select2').select2();
        });

        $(document).on('click', '.btn-delete', function() {
            var actionDelete = confirm('Are you sure?');
            if (actionDelete) {
                var dom;
                var id = $(this).data('id');
                var relation = $(this).data('relation');

                if (id) {
                    dom = `<input class="` + relation + `-delete" type="hidden" name="` + relation +
                        `-delete[]" value="` + id + `">`;
                    $(this).parents('.box-body').append(dom);
                }

                $(this).parents('tr').remove();

                if (!$('tbody tr').length) {
                    $('.empty-data').show();
                }
            }
        });
    </script>
    <!-- End Relational Form table -->
@endsection
