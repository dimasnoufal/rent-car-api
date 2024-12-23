<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Car;
use App\Models\Account;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function allBooking()
    {
        $booking = Booking::all();

        return ResponseFormatter::success($booking, 'All Booking Data');
    }

    public function listBookingById(int $accountId)
    {
        $booking = Booking::where('account_id', $accountId)->get();

        return ResponseFormatter::success($booking, 'Booking Data by Account ID');
    }


    public function booking(Request $request, int $carId, int $accountId)
    {
        try {

            $validator = Validator::make($request->all(), [
                'nama_mobil' => 'required|string|max:255',
                'tahun_mobil' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'tanggal_pemesanan' => 'required|string|max:255',
                'tanggal_pengembalian' => 'required|string|max:255',
                'nama_pemesan' => 'required|string|max:255',
                'no_hp' => 'required|string|max:255',
                'foto_ktp' => 'required|file|mimes:jpg,png,jpeg',
                'foto_sim' => 'required|file|mimes:jpg,png,jpeg',
                'status' => 'nullable|string|max:255',
                'layanan_supir' => 'nullable|string|max:255',
                'total_harga' => 'nullable|string|max:255',
            ]);

            $car = Car::find($carId);
            $account = Account::find($accountId);

            if (!$account) {
                return ResponseFormatter::error(null, 'Account not found', 404);
            }

            if (!$car) {
                return ResponseFormatter::error(null, 'Car not found', 404);
            }

            $car->quantity = $car->quantity - 1;
            $car->save();

            $file1 = $request->file('foto_ktp');
            $fileName1 = uniqid() . '.' . $file1->getClientOriginalExtension();
            $path1 = $file1->move(public_path('foto_ktp'), $fileName1);

            $file2 = $request->file('foto_sim');
            $fileName2 = uniqid() . '.' . $file2->getClientOriginalExtension();
            $path2 = $file2->move(public_path('foto_sim'), $fileName2);

            $booking = Booking::create([
                'account_id' => $accountId,
                'car_id' => $carId,
                'nama_mobil' => $request->nama_mobil,
                'tahun_mobil' => $request->tahun_mobil,
                'alamat' => $request->alamat,
                'tanggal_pemesanan' => $request->tanggal_pemesanan,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'nama_pemesan' => $request->nama_pemesan,
                'no_hp' => $request->no_hp,
                'foto_ktp' => asset('foto_ktp/' . $fileName1),
                'foto_sim' => asset('foto_sim/' . $fileName2),
                'status' => $request->status ?? 'BOOKING',
                'layanan_supir' => $request->layanan_supir ?? 'NO',
                'total_harga' => $request->total_harga ?? 0,
            ]);

            // return ResponseFormatter::success($booking, 'Booking Success');

            return ResponseFormatter::success([
                'booking' => $booking,
                'car' => $car,
                'account' => $account,
            ], 'Booking successfully created');
        } catch (Exception $exception) {
            return ResponseFormatter::error(null, $exception);
        }
    }

    // buatkan saya kode untuk mengedit data booking tapi hanya status saja
    public function editStatus(Request $request, int $id)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return ResponseFormatter::error(null, 'Booking not found', 404);
            }

            // Periksa apakah status diubah menjadi 'done'
            if ($request->status === 'DONE') {
                $car = Car::find($booking->car_id);
                if ($car) {
                    $car->quantity += 1;
                    $car->save();
                }
            }

            $booking->status = $request->status;
            $booking->save();

            return ResponseFormatter::success($booking, 'Booking status updated');
        } catch (Exception $exception) {
            return ResponseFormatter::error(null, $exception->getMessage());
        }
    }
}
