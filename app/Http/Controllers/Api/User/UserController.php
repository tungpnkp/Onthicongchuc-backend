<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = Auth::user();
            $type = $user->type;
            if (!empty($type)) {
                $type = json_decode($type, true);
            } else {
                $type = [];
            }
            $member = $user->toArray();
            $member['type'] = $type;
            $data['user'] = $member;
            return $this->sendResponse($data, "Thông tin cá nhân!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage()]);
        }
    }

    public function UpdateProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'name' => 'required',
//                'type' => 'required',
                'code' => 'required|unique:users,code,' . $user->id,
                'email' => 'required|unique:users,email,' . $user->id
            ], [
                'name.required' => "Nhập tên!",
//                'type.required' => "Chọn vai trò thành viên!",
                'code.required' => "Nhập số báo danh!",
                'code.unique' => "Số báo danh đã tồn tại!",
                'email.required' => "Email đã tồn tại!",
            ]);
            if ($validator->fails()) {
                return $this->sendError("Vui lòng điền đầy đủ thông tin!", $validator->errors(), 200);
            }
            $dataUser = $request->only('name', 'code', 'phone', 'email', 'address', 'gender');
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('avatars'), $imageName);
                $dataUser = array_merge($dataUser, ['image' => asset("avatars/" . $imageName)]);
            }
            $user->update($dataUser);

            $type = $user->type;
            if (!empty($type)) {
                $type = json_decode($type, true);
            } else {
                $type = [];
            }
            $member = $user->toArray();
            $member['type'] = $type;
            $data['user'] = $member;
            return $this->sendResponse($data, "Cập nhật thành công!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage(), 200]);
        }

    }

    public function ChangePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|min:8',
                'password_new' => 'required|min:8|confirmed',
            ], [
                'old_password.required' => "Chưa nhập mật khẩu!",
                'old_password.min' => "Mật khẩu phải nhiều hơn 8 ký tự!",
                'password_new.required' => "Chưa nhập mật khẩu mới!",
                'password_new.min' => "Mật khẩu mới phải nhiều hơn 8 ký tự!",
                'password_new.confirmed' => "Nhập lại mật khẩu không khớp!",
            ]);
            if ($validator->fails()) {
                return $this->sendError("Vui lòng điền đầy đủ thông tin!", $validator->errors(), 200);
            }
            if ((Hash::check($request->get('old_password'), $user->password)) == false) {
                return $this->sendError("Sai mật khẩu cũ!", [], 200);
            } else if ((Hash::check($request->get('password_new'), $user->password)) == true) {
                return $this->sendError("Mật khẩu mới phải khác với mật khẩu hiện tại!", [], 200);
            } else {
                $user->update(['password' => Hash::make($request->get('password_new'))]);
                $type = $user->type;
                if (!empty($type)) {
                    $type = json_decode($type, true);
                } else {
                    $type = [];
                }
                $member = $user->toArray();
                $member['type'] = $type;
                $data['user'] = $member;
                return $this->sendResponse($data, "Đổi mật khẩu thành công!");
            }
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage(), 200]);
        }

    }

    public function ForgotPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required'
            ], [
                'email.required' => "Chưa nhập email!"
            ]);
            if ($validator->fails()) {
                return $this->sendError("Vui lòng điền đầy đủ thông tin!", $validator->errors(), 200);
            }
            $user = $this->userService->findByField('email', $request->get('email'))->first();
            if (is_object($user)) {

                $otp = $this->userService->checkExits();
                $otp = $user->update(['otp' => $otp]);
                if ($otp) {
                    $this->userService->sendMail($request, $user);
                    $type = $user->type;
                    if (!empty($type)) {
                        $type = json_decode($type, true);
                    } else {
                        $type = [];
                    }
                    $member = $user->toArray();
                    $member['type'] = $type;
                    return $this->sendResponse($member, "Mã OTP đã được gửi tới email của bạn!");
                } else {
                    return $this->sendError("Không gửi được otp!", [], 200);
                }
            } else {
                return $this->sendError("Không tìm thấy tài khoản!", [], 200);
            }
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage(), 200]);
        }

    }

    public function ResetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'password' => 'required|min:8|confirmed'
            ], [
                'otp.required' => "Chưa nhập OTP!",
                'password.required' => "Chưa nhập mật khẩu mới!",
                'password.min' => "Mật khẩu mới phải nhiều hơn 8 ký tự!",
                'password.confirmed' => "Nhập lại mật khẩu không khớp!",
            ]);
            if ($validator->fails()) {
                return $this->sendError("Vui lòng điền đầy đủ thông tin!", $validator->errors(), 200);
            }
            $user = $this->userService->findByField('otp', $request->get('otp'))->first();
            if (is_object($user)) {
                $update = $user->update(['password' => Hash::make($request->get('password')), 'otp' => null]);
                $type = $user->type;
                if (!empty($type)) {
                    $type = json_decode($type, true);
                } else {
                    $type = [];
                }
                $member = $user->toArray();
                $member['type'] = $type;
                if ($update) {
                    return $this->sendResponse($member, "Tạo mật khẩu thành công!");
                } else {
                    return $this->sendError("Không tạo được mật khẩu!", [], 200);
                }
            } else {
                return $this->sendError("OTP đã hết hạn!", [], 200);
            }
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage(), 200]);
        }
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'password' => 'required',
            ], [
                'code.required' => "Nhập số báo danh!",
                'password.required' => "Nhập mật khẩu!",
            ]);
            if ($validator->fails()) {
                return $this->sendError("Vui lòng điền đầy đủ thông tin!", $validator->errors(), 200);
            }
            if (Auth::guard("user")->attempt(['code' => $request->get('code'), 'password' => $request->get('password'), 'status' => 1])) {
                $user = Auth::guard("user")->user();

                if ($user->status == 1) {
                    if (!empty($user->device_code)) {
                        if ($user->device_code == $request->get('device_code')) {
                            $data['token'] = $user->createToken('LoginUser')->accessToken;
                            $type = $user->type;
                            if (!empty($type)) {
                                $type = json_decode($type, true);
                            } else {
                                $type = [];
                            }
                            $member = $user->toArray();
                            $member['type'] = $type;
                            $data['user'] = $member;
                            $message = "Đăng nhập thành công!";
                            return $this->sendResponse($data, $message);
                        } else {
                            Auth::guard("user")->logout();
                            $message = "Tài khoản này không được đăng nhập trên thiết bị này!";
                            return $this->sendError($message, []);
                        }
                    } else {
                        if (!empty($request->get('device_code'))){
                            $user->update(['device_code' => $request->get('device_code')]);
                        }

                        $data['token'] = $user->createToken('LoginUser')->accessToken;
                        $type = $user->type;
                        if (!empty($type)) {
                            $type = json_decode($type, true);
                        } else {
                            $type = [];
                        }
                        $member = $user->toArray();
                        $member['type'] = $type;
                        $data['user'] = $member;
                        $message = "Đăng nhập thành công!";
                        return $this->sendResponse($data, $message);
                    }
                } else {
                    Auth::guard("user")->logout();
                    $message = "Tài khoản bị khóa!";
                    return $this->sendError($message, []);
                }


            } else {
                $message = "Sai tài khoản hoặc mật khẩu!";
                return $this->sendError($message, [], 200);
            }
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage(), 200]);
        }
    }
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'code' => ['required', 'unique:users', 'max:6', 'min:6'],
                'email' => ['required', 'email', 'unique:users'],
                'phone' => ['required', 'unique:users'],
                'password' => ['required', 'confirmed', 'min:8', 'max:16'],
            ], [
                'name.required' => "Nhập tên!",
                'code.required' => "Nhập số báo danh!",
                'code.unique' => "Số báo danh đã tồn tại!",
                'code.max' => "Số báo danh phải chứa 6 số!",
                'code.min' => "Số báo danh phải chứa 6 số!",
                'email.required' => "Nhập email!",
                'email.unique' => "Email đã tồn tại!",
                'phone.required' => "SĐT email!",
                'phone.unique' => "SĐT đã tồn tại!",
                'password.required' => "Chưa nhập mật khẩu!",
                'password.comfirmed' => "Mật khẩu nhập lại không đúng!",
                'password.min' => "Mật khẩu có ít nhất 8 ký tự!",
                'password.max' => "Mật khẩu có nhiều nhất 16 ký tự!",
            ]);
            if ($validator->fails()) {
                return $this->sendError("Vui lòng điền đầy đủ thông tin!", $validator->errors());
            }
            $data = array_merge($request->only('name','code','email','phone'),['password'=>bcrypt($request->get('password')),'status'=>0]);
            $user = $this->userService->create($data);
            $this->userService->sendRegisterMail($data);
            $message = "Đăng nhập thành công!";
            return $this->sendResponse($user->toArray(), $message);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage(), 200]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return $this->sendResponse([], "Đã đăng xuất!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", ['error' => $exception->getMessage(), 200]);
        }
    }
}
