<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function index($classId)
    {
        $user = Auth::user();
        if ($user->is_teacher) {

            $classes = $user->lessons->classes;
            var_dump($user);
        } else {
            $classes = Classes::where('status', 1)->get();
        }

        /*
            Sau đó, bạn có thể sử dụng phương thức with để tải các mối quan hệ liên quan đến Product và Category và sử dụng các phương thức truy cập để lấy danh sách các sản phẩm và các danh mục của chúng:

$user = User::with('products.category')->find($user_id);
$products = $user->products;
$categories = $products->pluck('category')->unique();
Trong ví dụ trên, with('products.category') được sử dụng để tải các mối quan hệ liên quan đến Product và Category. Sau đó, bạn có thể truy cập danh sách các sản phẩm và danh mục của chúng bằng cách sử dụng phương thức truy cập products và category. Lưu ý rằng pluck('category') được sử dụng để lấy danh sách các danh mục của các sản phẩm và unique() được sử dụng để loại bỏ các danh mục trùng lặp.
        */

        $class = Classes::findOrFail($classId)->where('status', 1);
        $lessons = $class->lessons;

        return view('lesson.index', compact('lessons', 'classes',));
    }
}
