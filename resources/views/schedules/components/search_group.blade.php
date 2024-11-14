<script>
    function searchGroup() {
        return {
            groupSuggestions: [],
            search: '',
            groups: [],
            getGroups() {
                if (this.search.length == 0) {
                    return;
                }
                fetch(
                        `{{ route('groups.suggestion') }}?search=${this.search}${!(this.courseId == 0 || this.courseId == 'null') ? '&course_id=' + this.courseId : ''}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            this.groupSuggestions = data.data
                        } else {
                            this.groupSuggestions = [];
                        }

                    }).catch(error => {
                        console.log(error);
                    })

            },
            chooseGroup(group) {
                this.groupSuggestions = [];
                this.search = '';
                if (this.groups.find(m => m.id == group.id)) {
                    return;
                }
                this.groups.push(group);
            }
        }
    }
</script>
<div x-data="searchGroup" x-init="$watch('courseId', (value) => {
    groupSuggestions = [];
    search = '';
    groups = [];
})">
    <div class="input-group">
        <input type="text" class="form-control" id="search" name="search" placeholder="Search..."
            @input.debounce.500ms="getGroups()" @keydown.enter.prevent="" x-model="search" autocomplete="off">
        <div class="input-group-append">
            <button type="button" id="btn-add-member" class="btn btn-primary rounded-right"
                @click.prevent="search = ''"><i class="fa fa-eraser"></i>
            </button>
        </div>
        <template x-if="groupSuggestions.length > 0">
            <div id="suggestion-member"
                style="position:absolute; top: calc(100% + 1px); background-color:white; border:1px solid #ccc; z-index:1; width:100%">

                <template x-for="group in groupSuggestions">
                    <button type="button" class="btn btn-link" style="width:100%; text-align:left"
                        @click.prevent="chooseGroup(group)">
                        <span x-text="group.name"></span>
                        <span class="badge badge-light">Mata Kuliah: <span x-text="group.course?.name"></span></span>
                    </button>
                </template>
            </div>
        </template>
    </div>
    <table x-cloack class="table table-bordered table-striped mt-2" id="table-member">
        <thead>
            <th class="w-75">Nama</th>
            <th>Action</th>
        </thead>
        <tbody>
            <template x-for="group in groups">
                <tr>
                    <td x-text="group.name" class="w-75"></td>
                    <td>
                        <button type="button" class="btn-delete btn btn-danger btn-xs"
                            @click="groups.splice(groups.indexOf(group), 1)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </template>
            <template x-if="groups.length == 0">
                <tr>
                    <td colspan="2" class="text-center">Tidak ada kelompok</td>
                </tr>
            </template>
        <tbody>
    </table>


</div>
