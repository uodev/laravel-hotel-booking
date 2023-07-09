<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelDetails;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function reservation(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'hotel_name' => 'required',
            'hotel_price' => 'required',
            'person_count' => 'required',
            'count_day' => 'required',
            'checkin_date' => 'required',
            'phone' => 'required|string|min:10|max:11',
            'detailId' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        //Check reservation date is available
        $checkin_date = date('Y-m-d', strtotime($request->checkin_date));
        $checkout_date = date('Y-m-d', strtotime($request->checkin_date . ' + ' . $request->count_day . ' days'));
        $reservation = Reservation::where('hotel_id', $request->hotel_id)
            ->where('checkin_date', '<=', $checkin_date)
            ->where('checkout_date', '>=', $checkout_date)
            ->first();
        if ($reservation) {
            return redirect()->back()->withErrors('Bu tarihler arasında rezervasyon yapılamaz. Lütfen başka bir tarih, otel veya kalacağınız gün sayısı seçiniz.');
        }


        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->detail_id = $request->detailId;
        $reservation->hotel_id = $request->hotel_id;
        $reservation->hotel_name = $request->hotel_name;
        $reservation->hotel_price = $request->hotel_price;
        $reservation->person_count = $request->person_count;
        $reservation->phone = $request->phone;
        $reservation->count_day = $request->count_day;
        $reservation->checkin_date = $request->checkin_date;
        $checkout_date = date('Y-m-d', strtotime($request->checkin_date . ' + ' . $request->count_day . ' days'));
        $reservation->checkout_date = $checkout_date;
        $reservation->save();

        return redirect()->back()->with('success', 'Rezervasyonunuz başarıyla oluşturuldu.');
    }

    public function getReservationDetail($id)
    {
        $details = HotelDetails::where('hotel_id', $id)->get();
        return view('reservation_detail', compact('details'));
    }
}
