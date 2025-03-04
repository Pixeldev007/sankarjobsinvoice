<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.invoice.invoice_pdf') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            padding: 0px;
            margin: 0px;
            font-size: 11px;
        }

        @page {
            margin: -10px !important;
        }

        @if (getInvoiceCurrencyIcon($invoice->currency_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body >
    @php $styleCss = 'style'; @endphp
    <div class="preview-main client-preview mumbai-template">
        <div class="d" id="boxes">
            <div class="d-inner">
                <div class="top-border" style="background-color: #FFCA05;"></div>
                <div style="background-color: white;" class="page">
                    <table class=" bg-white w-100 m-0 h-100px" style="overflow:hidden;">
                        <tr>
                            <td class="p-0 m-0" style="width:66%; overflow:hidden !important; font-size: 30px; ">
                                <div class="bg-white h-125px" style="border-top-right-radius:30px; padding:15px; ">
                                <p class=" mb-1" style="font-size:22px; font-weight: 700;margin-top: -20px;">SANKAR ADD AGENCY</p>
                                <p class="text-nowrap text-start font-gray-600 fw-bold">
                                    49 ,SNG Nagar
                                    Ammapalayam - Tirupur <br>
                                    9677929316<br>
                                    tirupursankarjobs@gmail.com
                                    
                                </p>
                                    
                                </div>
                            </td>
                            <td class="bg-white p-0 m-0 h-125px"
                                style="width:33%;border-bottom-left-radius:30px; overflow:hidden;">
                                <div class="text-center p-4 h-120px"
                                    style=" background-color:  #FFCA05;">
                                    <img width="130px" height="130px" src="{{ getLogoUrl() }}" style="margin-top: -14px;"
                                        alt="logo">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="px-2  bg-white" style="margin-right:10px;">
                        <div >
                            <table class=" w-100">
                                <tbody>
                                    <tr style="vertical-align:top;">
                                        <td width="33.33%" class="pl-3">
                                            <p class=" mb-1" style="font-size:14px; font-weight: 700;">Bill To</p>
                                            <p class=" mb-1" style="font-size:15px; font-weight: 400;">{{ $client->company_name}}</p>
                                          
                                            <p class="mb-1  font-color-gray fs-6">
                                                <span class="font-gray-900">{{ $client->address }} </span>
                                            </p>
                                            @if (!empty($client->vat_no))
                                                <p class=" mb-0  font-color-gray fs-6">
                                                    <span class="font-gray-900">{{ $client->vat_no }} </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td width="33.33%" class=" text-end ">
                                           
                                             <p class=" mb-1 pt-2" style="font-size:15px; font-weight: 400; padding-top: -50px;">
                                                 Invoice No: #{{ $invoice->invoice_id }} </span>
                                             </p>
                                                  
                                              <p class=" mb-1 pr-2  font-color-gray fs-6">
                                                <strong> Invoice Amount: </strong>
                                                <span class="font-gray-900">
                                                 @if (isset($invoice) && !empty($invoice))
                                                    @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                                               ₹{{$invoiceItems->total}}
                                                    @endforeach
                                                @endif
                                                 </span>
                                            </p>
                                            <p class=" mb-1 font-color-gray fs-6">
                                                <strong> {{ __('messages.invoice.invoice_date') }}: </strong>
                                                <span class="font-gray-900">{{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }} </span>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="overflow-auto px-1 pb-1 py-3" >
                            <table class="invoice-table w-100">
                                <thead style="background-color:  #003087;">
                                    <tr>
                                        <th class="p-2 text-uppercase" style="width:5% !important;"> # </th>
                                        <th class="p-2 in-w-2 text-uppercase" style="width:45% !important;">{{ __('messages.product.product') }}
                                        </th>
                                        <th class="p-2 text-center text-uppercase" style="width:15% !important;">
                                            {{ __('messages.invoice.qty') }}
                                        </th>
                                        <th class="p-2 text-center  text-nowrap text-uppercase"
                                            style="width:15% !important;">
                                            {{ __('messages.product.unit_price') }}</th>
                                       
                                        <th class="p-2 text-end  text-nowrap text-uppercase"
                                            style="width:12% !important;">
                                            {{ __('messages.invoice.amount') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($invoice) && !empty($invoice))
                                        @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                                            <tr>
                                                <td class="p-2" style="width:5%;">
                                                    <span>{{ $key + 1 }}</span>
                                                </td>
                                                <td class="p-2 in-w-2">
                                                    <p class="fw-bold mb-0">
                                                        {{ isset($invoiceItems->product->name) ? $invoiceItems->product->name : $invoiceItems->product_name ?? __('messages.common.n/a') }}
                                                    </p>
                                                    @if (
                                                        !empty($invoiceItems->product->description) &&
                                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                    @endif
                                                    @if (
                                                        !empty($invoiceItems->product->description) &&
                                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                        <span
                                                            style="font-size: 12px; word-break: break-all !important">{{ $invoiceItems->product->description }}</span>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-center">
                                                    {{ number_format($invoiceItems->quantity, 2) }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    {{ isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price, $invoice->currency_id, true) : __('messages.common.n/a') }}
                                                </td>
                                               
                                                <td class="p-2 text-end">
                                                    ₹{{$invoiceItems->total}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                       
                        <div >
                            <table class="w-100">
                                <tr>
                                    <td style="vertical-align: bottom; width: 60%;">
                                         <div class="text-nowrap text-start font-gray-600 fw-bold pl-3">
                                            <p>
                                                <strong>PAYMENT INSTRUCTIONS</strong><br>
                                                <strong>Bank Transfer</strong>: Pan Card No :- LFRPS8556Q ., SANKAR ADD AGENCY., <br>
                                                Bank Name ( HDFC ) :- 50200090239673., IFSC CODE :- HDFC0002408., <br>
                                                INDIRA NAGAR - TIRUPPUR<br>
                                                <strong>By cheque:</strong> SANKAR ADD AGENCY<br>
                                                <strong>Other:</strong> Google pay & Phone Pay - 9789-9789-40
                                            </p>
                                        </div>
                                    </td>
                                  
                                    <td style="vertical-align:top; width:40%;">
                                        <table class="w-100">
                                            <tbody>
                                                <tr>
                                                    <td class=" px-2">
                                                        <strong>{{ __('messages.invoice.sub_total') }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 px-2 fw-bold">
                                                        ₹{{$invoiceItems->total}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=" px-2">
                                                        <strong>{{ __('messages.invoice.discount') }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 px-2 fw-bold">
                                                        @if ($invoice->discount == 0)
                                                            <span>{{ __('messages.common.n/a') }}</span>
                                                        @else
                                                            @if (isset($invoice) && $invoice->discount_type == \App\Models\Invoice::FIXED)
                                                                <b
                                                                    class="euroCurrency">{{ isset($invoice->discount) ? getInvoiceCurrencyAmount($invoice->discount, $invoice->currency_id, true) : __('messages.common.n/a') }}</b>
                                                            @else
                                                                {{ $invoice->discount }}<span
                                                                    {{ $styleCss }}="font-family: DejaVuSans">
                                                                    &#37; </span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    @php
                                                        $itemTaxesAmount = $invoice->amount + array_sum($totalTax);
                                                        $invoiceTaxesAmount =
                                                            ($itemTaxesAmount * $invoice->invoiceTaxes->sum('value')) /
                                                            100;
                                                        $totalTaxes = array_sum($totalTax) + $invoiceTaxesAmount;
                                                    @endphp
                                                    <td class=" px-2">
                                                        <strong>{{ __('messages.invoice.tax') }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 px-2 fw-bold">
                                                        {!! numberFormat($totalTaxes) != 0
                                                            ? '<b class="euroCurrency">' . getInvoiceCurrencyAmount($totalTaxes, $invoice->currency_id, true) . '</b>'
                                                            : __('messages.common.n/a') !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=" px-2">
                                                        <strong>{{ __('messages.invoice.total') }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 px-2 fw-bold">
                                                        ₹{{$invoiceItems->total}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="total-amount"
                                                {{ $styleCss }}="background-color:  #003087;">
                                                <tr>
                                                    <td class="p-2">
                                                        <strong>{{ __('messages.admin_dashboard.total_due') }}</strong>
                                                    </td>
                                                    <td class="text-end p-2">
                                                        <strong>{{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="float: right; margin-top: -20px;">
                             @if (!empty($invoice->paymentQrCode) && isset($invoice->paymentQrCode->qr_image))
                         <table >
                             <div>
                                <p>Signature</p>
                              </div>          
                             <div>
                                <img src="{{ $invoice->paymentQrCode->qr_image }}" height="60" width="`100" alt="qr-code-image">
                             </div>
                         </table>
                         
                         @endif
                        </div>
                        
                       <div class="container px-2">
                         <div >
                     <div>
                    <!-- File Section -->
                   @if (!empty($invoice->file))
                        @php
                            // Convert the comma-separated string to an array
                            $files = explode(',', $invoice->file);
                        @endphp
                        <div style="display: flex; flex-wrap: wrap; ">
                            <table style="width: 100%;">
                                @foreach ($files as $index => $file)
                                    @if ($index % 3 == 0)
                                        <tr>
                                    @endif
                                    <td style="width: 33.33%; text-align: center;">
                                        <img src="{{ url($file) }}" alt="Photos" style="width: 225px; height: 300px; padding-top: 80px;">
                                    </td>
                                    @if (($index + 1) % 3 == 0)
                                        </tr>
                                    @endif
                                @endforeach
                                @if (count($files) % 3 != 0)
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endif
                    
                    <table style="width: 100%;">
                        <td style="width: 60%;">
                            @if (!empty($invoice->note))
                                <div>
                                    <h6 class="font-gray-900 pt-2 text-center px-4">
                                        <b>{{ __('messages.client.notes') }}:</b>
                                    </h6>
                                    <p class="font-gray-600 text-center px-4">
                                        {!! nl2br(e($invoice->note)) !!}
                                    </p>
                                </div>
                            @else
                                <div>
                                    <!--<h6 class="font-gray-900 pt-2 text-start px-4">-->
                                    <!--    <b>{{ __('messages.client.notes') }}:</b>-->
                                    <!--</h6>-->
                                    <!--<p class="font-gray-600 text-start px-4">-->
                                    <!--    {{ __('messages.common.not_available') }}-->
                                    <!--</p>-->
                                </div>
                            @endif
                        </td>

                        <td style="width: 40%;">
                             <div >
                                <h6 class="font-gray-900 pt-4 text-start px-6 "><b>{{ __('messages.invoice.terms') }}</b></h6>
                                <p class="font-gray-600  text-start px-6"> To be paid immediately upon invoice.</p>
                            </div>
                        </td>
                    </table>

                    <!-- Left column for notes and terms -->
                   
                     
                </div>
            </div>
             <div>
                        <table class="w-100 bg-white" style="margin: 20px 0px -5px 7px;">
                            <tr>
                                <td class=" p-0 h-30px" style="width:80% !important; overflow:hidden;">
                                    <div class="bg-white h-30px"
                                        style=" border-bottom-right-radius:30px; padding:24px;">
                                    </div>
                                </td>
                                <td class="bg-white p-0 h-30px"
                                    style="width:20%; border-top-left-radius:35px; overflow:hidden;">
                                    <div class="text-end h-30px"
                                        style="background-color:  #FFCA05;  padding:24px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="bottom-border" style="background-color:  #FFCA05; margin: 0px 0px -0px 7px;"></div>
              

                    </div>
                   
            </div>
        </div>
    </div>
</body>

</html>
