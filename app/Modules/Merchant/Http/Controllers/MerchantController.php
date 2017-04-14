<?php

namespace App\Modules\Merchant\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Merchant\Http\Requests\MerchantPaymentRequest;
use App\Modules\Merchant\Http\Requests\MerchantRequest;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Merchant\Models\MerchantPayment;
use App\Modules\Merchant\Services\MerchantService;
use Auth;
use Request;

class MerchantController extends Controller
{

    public function __construct(MerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
        $this->tag = Request::segment(2) == 'store' ? 'store' : 'merchant';
    }

    public function index()
    {
        return view('merchant::' . $this->tag . '.list');
    }

    public function datagrid()
    {
        $result = $this->merchantService->getList();
        return response()->json($result);
    }

    public function add()
    {
        return view('merchant::' . $this->tag . '.add');
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        $data['merchant'] = Merchant::with('account', 'parent', 'channel', 'department', 'industry')->find($id);
        return view('merchant::' . $this->tag . '.edit', $data);
    }

    public function save(MerchantRequest $merchantRequest)
    {
        $function = 'save' . (is_null($this->tag) || $this->tag == 'merchant' ? '' : ucfirst($this->tag));
        $result = $this->merchantService->$function($merchantRequest);
        return response()->json(['success' => $result === true, 'msg' => $result === false ? '保存失败！' : ($result === true ? '保存成功！' : $result)]);
    }

    public function destroy()
    {
        $result = $this->merchantService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function info()
    {
        $user = Auth::user();
        if ($user->mapping_type == 'merchants') {
            $id = $user->mapping_id;
        } else {
            return response('当前用户不是商户账户！');
            // $id = 11;
        }

        $data['merchant'] = Merchant::with('account', 'parent', 'channel', 'department', 'industry', 'provinceInfo', 'cityInfo')->find($id);

        return view('merchant::merchant.info', $data);
    }

    public function examine()
    {
        $id = Request::input('id', 0);
        $data['merchant'] = Merchant::with('parent')->find($id);

        return view('merchant::merchant.examine', $data);
    }

    public function activate()
    {
        $id = Request::input('id', 0);
        $data['merchant'] = Merchant::with('parent')->find($id);

        return view('merchant::merchant.activate', $data);
    }

    public function payments()
    {
        $result = $this->merchantService->getPayments();
        return response()->json($result);
    }

    public function addPayment()
    {
        $id = Request::input('merchant_id', 0);
        $data['merchant'] = Merchant::find($id);
        return view('merchant::merchant.payment.add', $data);
    }

    public function editPayment()
    {
        $id = Request::input('merchant_payment_id', 0);
        $data['merchantPayment'] = MerchantPayment::with('account', 'payment', 'merchant')->find($id);
        return view('merchant::merchant.payment.edit', $data);
    }

    public function activatePayment()
    {
        $id = Request::input('id', 0);
        $data['merchantPayment'] = MerchantPayment::with('account', 'payment', 'merchant')->find($id);
        return view('merchant::merchant.payment.activate', $data);
    }

    public function savePayment(MerchantPaymentRequest $merchantPaymentRequest)
    {
        $result = $this->merchantService->savePayment($merchantPaymentRequest);
        return response()->json(['success' => $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function billingAccounts($merchantId)
    {
        $result = $this->merchantService->getAllBillingAccounts($merchantId);
        return response()->json($result);
    }

    public function departments()
    {
        $result = $this->merchantService->getDepartments();
        return response()->json($result);
    }

}
