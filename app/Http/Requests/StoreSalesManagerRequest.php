<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreSalesManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'address'=>'required|min:3|max:255',
            'division_id'=>'required|numeric',
            'district_id'=>'required|numeric',
            'area_id'=>'required|numeric',
            'shop_id'=>'required|numeric',
            'nid'=>'required',
            'email'=>'required|min:2|max:50|string|unique:suppliers',
            'password'=>'required',Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),
            'phone'=>'required|numeric|unique:suppliers',
            'name'=>'required|min:2|max:50|string',
            'bio'=>'max:1000',
            'landmark'=>'max:255',
            'photo'=>'required',
            'nid_photo'=>'required',
            'status'=>'required|numeric',
        ];
    }
}
