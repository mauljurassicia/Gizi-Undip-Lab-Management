<script>
    function inputMembers() {
        return {
            membersSuggest: [],
            search: '',
            membersSearch: [],
            getMembers() {
                if (this.search.length == 0) {
                    return;
                }
                fetch(`{{ route('groups.members.suggestion') }}?search=${this.search}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            this.membersSuggest = data.data
                        } else {
                            this.membersSuggest = [];
                        }

                    }).catch(error => {
                        console.log(error);
                    })

            },
            chooseMember(member) {
                this.membersSuggest = [];
                this.search = '';
                if (this.membersSearch.find(m => m.id == member.id)) {
                    return;
                }
                this.membersSearch.push(member)
            }
        }
    }
</script>
<div x-data="inputMembers">
    {!! Form::label('typeId', 'Pengunjung: ', ['class' => 'mt-3']) !!}
    <div class="input-group">
        <input type="text" class="form-control" id="search" name="search" placeholder="Search..."
            @input.debounce.500ms="getMembers()" @keydown.enter.prevent="" x-model="search" autocomplete="off">
        <div class="input-group-append">
            <button type="button" id="btn-add-member" class="btn btn-primary rounded-right"
                @click.prevent="search = ''"><i class="fa fa-eraser"></i>
            </button>
        </div>
        <template x-if="membersSuggest.length > 0">
            <div id="suggestion-member"
                style="position:absolute; top: calc(100% + 1px); background-color:white; border:1px solid #ccc; z-index:1">

                <template x-for="member in membersSuggest">
                    <button type="button" class="btn btn-link" style="width:100%; text-align:left"
                        @click.prevent="chooseMember(member)">
                        <span x-text="member.name"></span>
                        <span class="badge badge-light">Nomor Identitas: <span
                                x-text="member.identity_number"></span></span>
                        <span class="badge badge-light">Role: <span
                                x-text="member.roles[0]?.name?.toUpperCase()"></span></span>
                        <span class="badge badge-light">Email: <span x-text="member.email"></span></span>
                    </button>
                </template>
            </div>
        </template>

    </div>
    <div class="mt-2">
        <template x-for="member in membersSearch" :key="member.id">

            <span class="badge badge-light mr-1 mb-1"><span x-text="member.name"></span> <span
                    x-text="member.identity_number"></span>
                {!! Form::hidden('typeId[]', null, ['class' => 'form-control', 'x-model' => 'member.id']) !!}
                <button type="button" class="btn btn-xs btn-icon p-0" @click.prevent="membersSearch = membersSearch.filter(m => m.id != member.id)">
                    <i class="fa fa-times"></i>
                </button>
            </span>

        </template>
    </div>

</div>
