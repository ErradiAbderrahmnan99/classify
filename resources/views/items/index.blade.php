@extends('layouts.main')

@section('title')
    {{ __('Advertisements') }}
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first"></div>
        </div>
    </div>
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div id="filters" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                            <div class="col-md-3">
                                <label for="filter-featured-premium">{{ __("Featured / Premium") }}</label>
                                <select class="form-control bootstrap-table-filter-control-featured_status" id="filter_featured_premium">
                                    <option value="">{{ __("All") }}</option>
                                    <option value="featured">{{ __("Featured") }}</option>
                                    <option value="premium">{{ __("Premium") }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_country">{{__("Country")}}</label>
                                <select class="form-control bootstrap-table-filter-control-country" id="filter_country_item_test">
                                    <option value="">{{__("All")}}</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="filter_city">{{__("City")}}</label>
                                <select name="city_id" class="form-control bootstrap-table-filter-control-city" id="filter_city_item">
                                    <option value="">{{__("All")}}</option>
                                </select>
                            </div>
                            </div>
                        <table class="table-borderless table-striped" aria-describedby="mydesc" id="table_list"
                               data-toggle="table" data-url="{{ route('advertisement.show','approved') }}" data-click-to-select="true"
                               data-side-pagination="server" data-pagination="true"
                               data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"
                               data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                               data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                               data-escape="true"
                               data-responsive="true" data-sort-name="id" data-sort-order="desc"
                               data-pagination-successively-size="3" data-table="items" data-status-column="deleted_at"
                               data-show-export="true" data-export-options='{"fileName": "item-list","ignoreColumn": ["operate"]}' data-export-types="['pdf','json', 'xml', 'csv', 'txt', 'sql', 'doc', 'excel']"
                               data-mobile-responsive="true" data-filter-control="true" data-filter-control-container="#filters" data-toolbar="#filters">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true">{{ __('ID') }}</th>
                                <th scope="col" data-field="name" data-sortable="true">{{ __('Name') }}</th>
                                <th scope="col" data-field="description" data-align="center" data-sortable="true" data-formatter="descriptionFormatter">{{ __('Description') }}</th>
                                <th scope="col" data-field="user.name" data-sort-name="user_name" data-sortable="true">{{ __('User') }}</th>
                                <th scope="col" data-field="price" data-sortable="true">{{ __('Price') }}</th>
                                <th scope="col" data-field="image" data-sortable="false" data-escape="false" data-formatter="imageFormatter">{{ __('Image') }}</th>
                                <th scope="col" data-field="gallery_images" data-sortable="false" data-formatter="galleryImageFormatter" data-escape="false">{{ __('Other Images') }}</th>
                                <th scope="col" data-field="latitude" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Latitude') }}</th>
                                <th scope="col" data-field="longitude" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Longitude') }}</th>
                                <th scope="col" data-field="address" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Address') }}</th>
                                <th scope="col" data-field="contact" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Contact') }}</th>
                                <th scope="col" data-field="country" data-sortable="true" data-filter-control="select" data-filter-data="" data-visible="true">{{ __('Country') }}</th>
                                <th scope="col" data-field="state" data-sortable="true" data-visible="true">{{ __('State') }}</th>
                                <th scope="col" data-field="city" data-sortable="true" data-filter-control="select" data-filter-data="" data-visible="true">{{ __('City') }}</th>
                                <th scope="col" data-field="featured_status" data-sortable="false" data-filter-control="select" data-filter-data="" data-formatter="featuredItemStatusFormatter">{{ __('Featured/Premium') }}</th>
                                <th scope="col" data-field="status" data-sortable="false" data-filter-control="select" data-filter-data="" data-escape="false" data-formatter="itemStatusFormatter">{{ __('Status') }}</th>
                                @can('item-update')
                                    <th scope="col" data-field="active_status" data-sortable="true" data-sort-name="deleted_at" data-visible="true" data-escape="false" data-formatter="statusSwitchFormatter">{{ __('Active') }}</th>
                                @endcan
                                <th scope="col" data-field="rejected_reason" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Rejected Reason') }}</th>
                                <th scope="col" data-field="expiry_date" data-sortable="true" data-visible="true">{{ __('Expiry Date') }}</th>
                                <th scope="col" data-field="created_at" data-sortable="false" data-visible="false" data-switchable="false">{{ __('Created At') }}</th>
                                <th scope="col" data-field="updated_at" data-sortable="false" data-visible="false" data-switchable="false">{{ __('Updated At') }}</th>
                                <th scope="col" data-field="user_id" data-sortable="false" data-visible="false" data-switchable="false">{{ __('User ID') }}</th>
                                <th scope="col" data-field="category_id" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Category ID') }}</th>
                                <th scope="col" data-field="likes" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Likes') }}</th>
                                <th scope="col" data-field="clicks" data-sortable="true" data-visible="false" data-switchable="false">{{ __('Clicks') }}</th>
                                @canany(['item-update','item-delete'])
                                    <th scope="col" data-field="operate" data-align="center" data-sortable="false" data-events="itemEvents" data-escape="false">{{ __('Action') }}</th>
                                @endcanany
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">{{ __('Advertisement Details') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="center" id="custom_fields"></div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <div id="editStatusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">{{ __('Status') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="edit-form" action="" method="POST" data-success-function="updateApprovalSuccess">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="status" class="form-select" id="status" aria-label="status">
                                        <option value="review">{{__("Under Review")}}</option>
                                        <option value="approved">{{__("Approve")}}</option>
                                        <option value="soft rejected">{{__("Soft Rejected")}}</option>
                                        <option value="permanent rejected">{{__("Permanent Rejected")}}</option>
                                    </select>
                                </div>
                            </div>
                            <div id="rejected_reason_container" class="col-md-12" style="display: none;">
                                <label for="rejected_reason" class="mandatory form-label">{{ __('Reason') }}</label>
                                <textarea name="rejected_reason" id="rejected_reason" class="form-control" placeholder={{ __('Reason') }}></textarea>
                                {{-- <input type="text" name="rejected_reason" id="rejected_reason" class="form-control"> --}}
                            </div>
                            <input type="submit" value="{{__("Save")}}" class="btn btn-primary mt-3">
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </section>
@endsection
@section('script')
    <script>
        function updateApprovalSuccess() {
            $('#editStatusModal').modal('hide');
        }

    </script>
@endsection
