@extends('layouts.app')
@section("title", "پنل مدیریت وبلاگ")
@section("content")

    <!-- Start Log In Area -->
    <section class="user-area-all-style log-in-area cart-area ptb-100" style="background: whitesmoke">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="contact-form-action">
                        <div class="form-heading text-center">
                            <h3 class="form-title">پنل مدیریت املاک</h3>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">

                                        <label for="house_name">نام ملک:</label>
                                        <input class="form-control" id="blog_title" type="text" name="house_name"
                                            placeholder="نام ملک">

                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="house_price">قیمیت ملک</label>
                                        <input class="form-control" id="house_price" type="text" name="house_price"
                                            placeholder="قیمیت ملک">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="house_main_image">تصویر اصلی ملک:</label>
                                        <input class="form-control" id="house_main_image" type="file" name="blog_main_image"
                                            placeholder="تصویر اصلی ملک:">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="house_images">تصاویر ملک</label>
                                        <input class="form-control" id="house_images" type="file" name="house_images[]"
                                            placeholder="تصاویر ملک">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="house_description">توضیحات ملک:</label>
                                        <textarea class="form-control" name="house_description" id="house_description"
                                            cols="30" rows="10" placeholder="توضیحات ملک:"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="select-box">
                                        <select name="house_room_count" class="form-control">
                                            <option value="0">تعداد اتاق ها</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="select-box">
                                        <select name="house_garage_count" class="form-control">
                                            <option value="0">تعداد گاراژ ها</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="house_region">منطقه ملک</label>
                                        <input class="form-control" id="house_region" type="text" name="house_region"
                                            placeholder="موضوع وبلاگ">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="house_postal_code">کدپستی</label>
                                        <input class="form-control" id="house_postal_code" type="text"
                                            name="house_postal_code" placeholder="موضوع وبلاگ">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="house_exact_location">ادرس ملک</label>
                                        <input class="form-control" id="house_exact_location" type="text"
                                            name="house_exact_location" placeholder="ادرس ملک">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="house_id">شناسه ملک</label>
                                        <input class="form-control" id="house_id" type="text" name="house_id"
                                            placeholder="شناسه ملک">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="select-box">
                                        <select name="house_type" class="form-control">
                                            <option value="0">نوع ملک</option>
                                            <option value="apartment">آپارتمان</option>
                                            <option value="villa">ویلا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="default-btn btn-two" type="submit">
                                        ثبت
                                        <i class="flaticon-left-arrow"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="cart-wraps">
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ملک</th>
                                        <th scope="col">نام ملک</th>
                                        <th scope="col">قیمت</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($houses as $house)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="/house/{{$house}}">
                                                    <img src="{{ \Storage::disk('s3')->url('images/properties/' . $house->house_main_image) }}"
                                                        alt="{{$house->house_name}}">
                                                </a>
                                            </td>

                                            <td class="product-name">
                                                <a href="/house/{{$house}}">{{$house->house_name}}</a>
                                            </td>

                                            <td class="product-price">
                                                <span class="unit-amount">{{$house->house_price}}</span>
                                            </td>
                                            <td class="product-subtotal">
                                                <a href="/house/{{$house}}/delete" class="remove">
                                                    <i class='bx bx-trash'></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                <div class="alert alert-danger" role="alert">
                                                    هیچ ملک پیدا نشد!
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Log In Area -->

@endsection