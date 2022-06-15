<div class="table-responsive mb-3">
    <table class="table">
        <thead>
        <tr>
            @foreach($columns as $column)
                @component('nos.crud::th', [
                    'order' => $column['order'],
                    'title' => trans('crud.'.$componentName.'.columns.'.$column['name']),
                    'columnName' => $column['name']
                ])
                @endcomponent
            @endforeach
            <th>{{trans('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in data.data">
            @foreach($columns as $column)
                @if(isset(${$column['name']}))
                    <td>
                        {{ ${$column['name']} }}
                    </td>
                @else
                    <td v-html="item.{{$column['name']}}">
                    </td>
                @endif
            @endforeach
            <td class="nowrap">
                @include('vuexy.partials.edit-buttons')
            </td>
        </tr>
        </tbody>
    </table>
</div>
<b-pagination v-if="!loading"
              v-model="data.current_page"
              :total-rows="data.total"
              :per-page="data.per_page"
              @change="getData"
></b-pagination>
