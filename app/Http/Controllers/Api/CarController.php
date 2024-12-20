<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{

    public function all()
    {
        $data = Car::all();

        return ResponseFormatter::success($data, 'Successfully Fetched');
    }

    public function allAvailableCar()
    {
        $data = Car::where('quantity', '>', 0)->get();

        return ResponseFormatter::success($data, 'Successfully Fetched');
    }

    public function topFiveCar()
    {
        $data = Car::where('quantity', '>', 0)
            ->orderBy('rating', 'desc')
            ->take(5)
            ->get();

        return ResponseFormatter::success($data, 'Successfully Fetched Top 5 Cars');
    }

    public function add(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpg,png,jpeg',
                'name' => 'required|string',
                'year' => 'required|string',
                'rating' => 'nullable|string',
                'about' => 'nullable|string',
                'price' => 'nullable|string',
                'quantity' => 'nullable|string',
                'feature1' => 'required|string',
                'feature2' => 'nullable|string',
                'feature3' => 'nullable|string',
                'feature4' => 'nullable|string',
            ]);

            if ($validate->fails()) {
                return ResponseFormatter::error(null, "Input Error", 300);
            }

            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $code_car = substr(str_shuffle($str_result), 0, 10);

            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('image_cars'), $fileName);

            $ci_price = (int) $request->price;
            $ci_quantity = (int) $request->quantity;


            $data = Car::create([
                'code_car' => $code_car,
                'image' => asset('image_cars/' . $fileName),
                'name' => $request->name,
                'year' => $request->year,
                'rating' => $request->rating ?? '0',
                'about' => $request->about ?? '',
                'price' => $ci_price ?? 0,
                'quantity' => $ci_quantity ?? 1,
                'feature1' => $request->feature1,
                'feature2' => $request->feature2 ?? '',
                'feature3' => $request->feature3 ?? '',
                'feature4' => $request->feature4 ?? '',
            ]);

            return ResponseFormatter::success($data, "Product Successfully Added");
        } catch (Exception $ex) {
            return ResponseFormatter::error(null, 'Internal Server Error', 500);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'nullable|file|mimes:jpg,png,jpeg',
                'name' => 'nullable|string',
                'year' => 'nullable|string',
                'rating' => 'nullable|string',
                'about' => 'nullable|string',
                'price' => 'nullable|string',
                'quantity' => 'nullable|string',
                'feature1' => 'nullable|string',
                'feature2' => 'nullable|string',
                'feature3' => 'nullable|string',
                'feature4' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), "Input Error", 422);
            }

            $car = Car::find($id);

            if (!$car) {
                return ResponseFormatter::error(null, 'Car not found', 404);
            }

            $fileName = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('image_cars'), $fileName);

                if ($car->image) {
                    unlink(public_path('image_cars/' . basename($car->image)));
                }
            }

            $ci_price = $request->price !== null ? (int)$request->price : $car->price;
            $ci_quantity = $request->quantity !== null ? (int)$request->quantity : $car->quantity;

            $car->update([
                'image' => $fileName ? asset('image_cars/' . $fileName) : $car->image,
                'name' => $request->name ?? $car->name,
                'year' => $request->year ?? $car->year,
                'rating' => $request->rating ?? $car->rating,
                'about' => $request->about ?? $car->about,
                'price' => $ci_price,
                'quantity' => $ci_quantity,
                'feature1' => $request->feature1 ?? $car->feature1,
                'feature2' => $request->feature2 ?? $car->feature2,
                'feature3' => $request->feature3 ?? $car->feature3,
                'feature4' => $request->feature4 ?? $car->feature4,
            ]);

            return ResponseFormatter::success($car, 'Car data successfully updated');
        } catch (Exception $ex) {
            return ResponseFormatter::error([
                'message' => "'Something went wrong",
                'error' => $ex->getMessage(),
            ], 'Internal Server Error', 500);
        }
    }
}
