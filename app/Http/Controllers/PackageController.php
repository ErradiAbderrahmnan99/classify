<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\PaymentTransaction;
use App\Models\UserFcmToken;
use App\Models\UserPurchasedPackage;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\FileService;
use App\Services\NotificationService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PackageController extends Controller {

    private string $uploadFolder;

    public function __construct() {
        $this->uploadFolder = 'packages';
    }

    public function index() {
        ResponseService::noAnyPermissionThenRedirect(['advertisement-listing-package-list', 'advertisement-listing-package-create', 'advertisement-listing-package-update', 'advertisement-listing-package-delete']);
        $category = Category::select(['id', 'name'])->where('status', 1)->get();
        $currency_symbol = CachingService::getSystemSettings('currency_symbol');
        return view('packages.item-listing', compact('category', 'currency_symbol'));
    }

    public function store(Request $request) {
        ResponseService::noPermissionThenSendJson('advertisement-listing-package-create');
        $validator = Validator::make($request->all(), [
            'name'                   => 'required',
            'price'                  => 'required|numeric',
            'discount_in_percentage' => 'required|numeric',
            'final_price'            => 'required|numeric',
            'duration_type'          => 'required|in:limited,unlimited',
            'duration'               => 'required_if:duration_type,limited',
            'item_limit_type'        => 'required|in:limited,unlimited',
            'item_limit'             => 'required_if:limit_type,limited',
            'icon'                   => 'required|mimes:jpeg,jpg,png|max:7168',
            'description'            => 'required',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }
        try {
            $data = [
                ...$request->all(),
                'duration'   => ($request->duration_type == "limited") ? $request->duration : "unlimited",
                'item_limit' => ($request->item_limit_type == "limited") ? $request->item_limit : "unlimited",
                'type'       => 'item_listing'
            ];
            if ($request->hasFile('icon')) {
                $data['icon'] = FileService::compressAndUpload($request->file('icon'), $this->uploadFolder);
            }
            Package::create($data);
            ResponseService::successResponse('Package Successfully Added', $data);
        } catch (Throwable $th) {
            ResponseService::logErrorResponse($th, "PackageController -> store method");
            ResponseService::errorResponse();
        }

    }

    public function show(Request $request) {
        ResponseService::noPermissionThenSendJson('advertisement-listing-package-list');
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'DESC';

        $sql = Package::where('type', 'item_listing');
        if (!empty($request->search)) {
            $sql = $sql->search($request->search);
        }
        $total = $sql->count();
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $result = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        foreach ($result as $key => $row) {
            $tempRow = $row->toArray();
            if (Auth::user()->can('advertisement-listing-package-update')) {
                $tempRow['operate'] = BootstrapTableService::editButton(route('package.update', $row->id), true);
            }
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function update(Request $request, $id) {
        ResponseService::noPermissionThenSendJson('advertisement-listing-package-update');
        $validator = Validator::make($request->all(), [
            'name'                   => 'required',
            'price'                  => 'required|numeric',
            'discount_in_percentage' => 'required|numeric',
            'final_price'            => 'required|numeric',
            'duration_type'          => 'required|in:limited,unlimited',
            'duration'               => 'required_if:duration_type,limited',
            'item_limit_type'        => 'required|in:limited,unlimited',
            'item_limit'             => 'required_if:limit_type,limited',
            'icon'                   => 'nullable|mimes:jpeg,jpg,png|max:7168',
            'description'            => 'required',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }

        try {
            $package = Package::findOrFail($id);

            $data = [
                //Type should not be updated
                ...$request->except('type'),
                'duration'   => ($request->duration_type == "limited") ? $request->duration : "unlimited",
                'item_limit' => ($request->item_limit_type == "limited") ? $request->item_limit : "unlimited"
            ];

            if ($request->hasFile('icon')) {
                $data['icon'] = FileService::compressAndReplace($request->file('icon'), $this->uploadFolder, $package->getRawOriginal('icon'));
            }

            $package->update($data);

            ResponseService::successResponse("Package Successfully Update");
        } catch (Throwable $th) {
            ResponseService::logErrorResponse($th, "PackageController ->  update");
            ResponseService::errorResponse();
        }
    }

    /* Advertisement Package */
    public function advertisementIndex() {
        ResponseService::noAnyPermissionThenRedirect(['featured-advertisement-package-list', 'featured-advertisement-package-create', 'featured-advertisement-package-update', 'featured-advertisement-package-delete']);
        $category = Category::select(['id', 'name'])->where('status', 1)->get();
        $currency_symbol = CachingService::getSystemSettings('currency_symbol');
        return view('packages.advertisement', compact('category', 'currency_symbol'));
    }

    public function advertisementShow(Request $request) {
        ResponseService::noPermissionThenSendJson('featured-advertisement-package-list');
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'DESC';

        $sql = Package::where('type', 'advertisement');
        if (!empty($request->search)) {
            $sql = $sql->search($request->search);
        }
        $total = $sql->count();
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $result = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        foreach ($result as $key => $row) {
            $tempRow = $row->toArray();
            $operate = '';
//            $operate = '&nbsp;&nbsp;<a  id="' . $row->id . '"  class="btn icon btn-primary btn-sm rounded-pill mt-2 edit_btn editdata"  data-bs-toggle="modal" data-bs-target="#editModal"   title="Edit"><i class="fa fa-edit edit_icon"></i></a>';
            if (Auth::user()->can('featured-advertisement-package-update')) {
                $operate .= BootstrapTableService::editButton(route('package.advertisement.update', $row->id), true);
            }

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function advertisementStore(Request $request) {
        ResponseService::noPermissionThenSendJson('featured-advertisement-package-create');
        $validator = Validator::make($request->all(), [
            'name'                   => 'required',
            'price'                  => 'required|numeric',
            'discount_in_percentage' => 'required|numeric',
            'final_price'            => 'required|numeric',
            'duration'               => 'nullable',
            'item_limit'             => 'nullable',
            'icon'                   => 'required|mimes:jpeg,jpg,png|max:7168',
            'description'            => 'required',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }
        try {
            $data = [
                ...$request->all(),
                'duration'   => !empty($request->duration) ? $request->duration : "unlimited",
                'item_limit' => !empty($request->item_limit) ? $request->item_limit : "unlimited",
                'type'       => 'advertisement'
            ];
            if ($request->hasFile('icon')) {
                $data['icon'] = FileService::compressAndUpload($request->file('icon'), $this->uploadFolder);
            }
            Package::create($data);
            ResponseService::successResponse('Package Successfully Added');
        } catch (Throwable $th) {
            ResponseService::logErrorResponse($th, "PackageController -> store method");
            ResponseService::errorResponse();
        }
    }


    public function advertisementUpdate(Request $request, $id) {
        ResponseService::noPermissionThenSendJson('featured-advertisement-package-update');
        $validator = Validator::make($request->all(), [
            'name'                   => 'nullable',
            'price'                  => 'required|numeric',
            'discount_in_percentage' => 'required|numeric',
            'final_price'            => 'required|numeric',
            'duration'               => 'nullable',
            'item_limit'             => 'nullable',
            'icon'                   => 'nullable|mimes:jpeg,jpg,png|max:7168',
            'description'            => 'nullable',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }

        try {
            $package = Package::findOrFail($id);

            $data = [
                //Type should not be updated
                ...$request->except('type'),
                'duration'   => !empty($request->duration) ? $request->duration : "unlimited",
                'item_limit' => !empty($request->item_limit) ? $request->item_limit : "unlimited"
            ];

            if ($request->hasFile('icon')) {
                $data['icon'] = FileService::compressAndReplace($request->file('icon'), $this->uploadFolder, $package->getRawOriginal('icon'));
            }
            $package->update($data);

            ResponseService::successResponse("Package Successfully Update");
        } catch (Throwable $th) {
            ResponseService::logErrorResponse($th, "PackageController ->  update");
            ResponseService::errorResponse();
        }
    }

    public function userPackagesIndex() {
        ResponseService::noPermissionThenRedirect('user-package-list');
        return view('packages.user');
    }

    public function userPackagesShow(Request $request) {
        ResponseService::noPermissionThenSendJson('user-package-list');
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'DESC';

        $sql = UserPurchasedPackage::with('user:id,name', 'package:id,name');
        if (!empty($request->search)) {
            $sql = $sql->search($request->search);
        }
        $total = $sql->count();
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $result = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        foreach ($result as $key => $row) {
            $rows[] = $row->toArray();
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function paymentTransactionIndex() {
        ResponseService::noPermissionThenRedirect('payment-transactions-list');
        return view('packages.payment-transactions');
    }

    public function paymentTransactionShow(Request $request) {
        ResponseService::noPermissionThenSendJson('payment-transactions-list');
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'DESC';

        $sql = PaymentTransaction::with('user')->orderBy($sort, $order);
        if (!empty($request->search)) {
            $sql = $sql->search($request->search);
        }
        $total = $sql->count();
        $sql->skip($offset)->take($limit);
        $result = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();

        foreach ($result as $key => $row) {
            $tempRow = $row->toArray();
            $tempRow['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-m-y H:i:s');
            $tempRow['updated_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('d-m-y H:i:s');
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function bankTransferIndex() {
        ResponseService::noPermissionThenRedirect('payment-transactions-list');
        return view('packages.bank-transfer');
    }
    public function bankTransferShow(Request $request) {
        ResponseService::noPermissionThenSendJson('payment-transactions-list');
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'DESC';

        $sql = PaymentTransaction::with('user')->where('payment_gateway' ,'BankTransfer')->orderBy($sort, $order);
        if (!empty($request->search)) {
            $sql = $sql->search($request->search);
        }
        $total = $sql->count();
        $sql->skip($offset)->take($limit);
        $result = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        foreach ($result as $key => $row) {
            $tempRow = $row->toArray();
            $tempRow['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-m-y H:i:s');
            $tempRow['updated_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('d-m-y H:i:s');
            $tempRow['operate'] = BootstrapTableService::editButton(route('package.bank-transfer.update-status', $row->id), true, '#editStatusModal', 'edit-status', $row->id);

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:succeed,rejected'
        ]);
        if ($validator->fails()) {
            return ResponseService::validationError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();
            ResponseService::noPermissionThenSendJson('item-update');

            $transaction = PaymentTransaction::findOrFail($id);
            $transaction->update([
                'payment_status' => $request->payment_status,
            ]);

            $userTokens = UserFcmToken::where('user_id', $transaction->user_id)->pluck('fcm_token')->toArray();

            if ($request->payment_status === 'succeed') {
                $package = Package::find((int) $transaction->order_id);

                if ($package) {
                    UserPurchasedPackage::create([
                        'package_id'  => $package->id,
                        'user_id'     => $transaction->user_id,
                        'start_date'  => Carbon::now(),
                        'end_date'    => $package->duration == "unlimited" ? null : Carbon::now()->addDays($package->duration),
                        'total_limit' => $package->item_limit == "unlimited" ? null : $package->item_limit,
                        'payment_transactions_id' => $transaction->id,
                    ]);
                }

                if (!empty($userTokens)) {
                    $title = "Package Purchased";
                    $body = 'Amount :- ' . $transaction->amount;
                    NotificationService::sendFcmNotification($userTokens, $title, $body, 'payment');
                }
            } elseif ($request->payment_status === 'rejected') {
                if (!empty($userTokens)) {
                    $title = "Payment Rejected";
                    $body = "Your payment of " . $transaction->amount . " has been rejected.";
                    NotificationService::sendFcmNotification($userTokens, $title, $body, 'payment');
                }
            }

            DB::commit();
            return ResponseService::successResponse('Payment Status Updated Successfully');
        } catch (Throwable $th) {
            DB::rollBack();
            ResponseService::logErrorResponse($th, 'PackageController ->updateStatus');
            return ResponseService::errorResponse('Something Went Wrong');
        }
    }

}
