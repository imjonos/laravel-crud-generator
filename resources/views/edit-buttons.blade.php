<div class="dropdown">
    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
        <svg data-v-32017d0f="" xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="align-middle text-body feather feather-more-vertical">
            <circle data-v-32017d0f="" cx="12" cy="12" r="1"></circle>
            <circle data-v-32017d0f="" cx="12" cy="5" r="1"></circle>
            <circle data-v-32017d0f="" cx="12" cy="19" r="1"></circle>
        </svg>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item" :href="link+'/'+item.id+'/edit'">
            <svg data-v-32017d0f="" xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round" class="feather feather-edit">
                <path data-v-32017d0f="" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path data-v-32017d0f="" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <span>@lang('crud.buttons.edit')</span>
        </a>
        @if(Route::current()->getName() !== 'offer_page_sections.index' && Route::current()->getName() !== 'settings.index')
            <a class="dropdown-item" href="#"  @click="destroy(item.id)">
            <svg data-v-32017d0f="" xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round" class="feather feather-trash">
                <polyline data-v-32017d0f="" points="3 6 5 6 21 6"></polyline>
                <path data-v-32017d0f=""
                      d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
            <span>@lang('crud.buttons.delete')</span>
        </a>
        @endif
    </div>
</div>
