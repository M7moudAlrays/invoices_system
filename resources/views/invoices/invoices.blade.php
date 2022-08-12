@extends('layouts.master')

@section('title')
عرض الفواتير
@endsection
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>

@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h2 class="content-title mb-0"> قـــائمة الفواتيـــــر </h2>
						</div>
					</div>
					
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

@if (session()->has('edit'))
        <div class="alert alert-warrning alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif

@if (session()->has('Status_Update'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم تحديث حالة الدفع بنجاح",
                    type: "success"
                })
            }

        </script>
@endif


@if (session()->has('delete'))
        <script>
            window.onload = function() 
			{
                notif({
                    msg: "تم حذف الفاتورة بنجاح",
                    type: "success"
                })
            }
        </script>
@endif

@if (session()->has('restore_invoice'))
        <script>
            window.onload = function() 
			{
                notif({
                    msg: "تم استرجاع الفاتورة بنجاح",
                    type: "success"
                })
            }
        </script>
@endif
					<!--div-->
					<div class="col-xl-12">
						<div class="card mg-b-20">

							<div class="card-header pb-0">
								<a href="{{route('Exp_Inv')}}" class="modal-effect btn btn-m btn-success" style="color:white">
									<i class="fas fa-file-export"> </i>&nbsp;تصديـــر فاتورة
								</a>

							</div>

							<div class="card-header pb-0">
										{{-- @can('اضافة فاتورة') --}}
											<a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
													class="fas fa-plus"></i>&nbsp; اضافة فاتورة
											</a>					
							</div>
							
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap">
										<thead>
											<tr>
												<th class="border-bottom-0">#</th>
												<th class="border-bottom-0">رقم الفاتوره</th>
												<th class="border-bottom-0">تاريخ الفاتوره </th>
												<th class="border-bottom-0">تاريخ الإستحقاق</th>									
												<th class="border-bottom-0"> المنتج </th>
												<th class="border-bottom-0">القسم</th>
												<th class="border-bottom-0">الخصم</th>
												<th class="border-bottom-0">نسبة الضريبة</th>
												<th class="border-bottom-0">قيمة الضريبة</th>
												<th class="border-bottom-0">الإجمالى</th>
												<th class="border-bottom-0">الحالة</th>
												<th class="border-bottom-0">العمليــات</th>
												<th class="border-bottom-0">ملاحظات</th>
												
											</tr>
											
										</thead>
										<tbody>
											<?php $i=0 ; ?>
											@foreach ($invoices as $invoice)
											@php $i++ @endphp
											<tr>
												<td>{{$i}}</td>
												<td>{{$invoice->invoice_number}}</td>
												<td>{{$invoice->invoice_Date}}</td>
												<td>{{$invoice->Due_date}}</td>
												<td>
													<a href="{{url('invoices_details')}}/{{$invoice->id}}"> {{$invoice->product}}</a>
												</td>
												<td>{{$invoice->section['section_name']}} </td>
												<td>{{$invoice->Discount}}</td>
												<td>{{$invoice->Rate_VAT}}</td>
												<td>{{$invoice->Value_VAT}}</td>
												<td>{{$invoice->Total}}</td>
												<td>
													@if ($invoice->Value_Status == 1)
														<span class="badge badge-success"> {{$invoice->Status}} </span>
														@elseif ($invoice->Value_Status == 2)
														<span class="badge badge-danger"> {{$invoice->Status}} </span>
														@else 
														<span class="badge badge-warning"> {{$invoice->Status}} </span>
													@endif
												</td>
												<td>
													<div class="dropdown">
														<button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary"
														data-toggle="dropdown" id="dropdownMenuButton" type="button"> العمليات على الفاتورة <i class="fas fa-caret-down ml-1"></i></button>
														<div  class="dropdown-menu tx-13">
															<a class="dropdown-item" href="{{route ('invoices.edit',$invoice->id)}}"> تعديل الفاتورة </a>

															<a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
															   data-toggle="modal" data-target="#delete_invoice">
															   <i class="text-danger fas fa-trash-alt"> </i> &nbsp;&nbsp; حذف الفاتورة
															</a>
																														
															<a class="dropdown-item" href="{{route ('invoices.show',$invoice->id)}}">
																<i class="text-warning  fab fa-accessible-icon"> </i> &nbsp;&nbsp;   تعديل حالة الدفع 
															</a>
															
                                                        	<a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                            	data-toggle="modal" data-target="#Transfer_invoice">
																<i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;  نقل إلي الأرشيف
															</a>

															<a class="dropdown-item" href="{{route ('print_invoice',$invoice->id)}}" >
																<i class="text-info fas fa-exchange-alt"></i>&nbsp;&nbsp; طباعة الفاتورة
															</a>

														</div>
													</div>
												</td>
												<td>{{$invoice->note}}</td>
											</tr>	
											@endforeach																					
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->

<!-- حذف الفاتورة -->
<div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<form action="{{ route('invoices.destroy','d') }}" method="post">
							{{ method_field('delete') }}
							{{ csrf_field() }}
			</div>
					<div class="modal-body">
						هل انت متاكد من عملية الحذف ؟
						<input type="text" name="invoice_id" id="invoice_id" value="">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
						<button type="submit" class="btn btn-danger">تاكيد</button>
					</div>
				</form>
		</div>
	</div>
</div> 

<!-- ارشيف الفاتورة -->
    <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form action= "{{ route('invoices.destroy','w') }}" method="post">
                        {{ method_field('delete') }} 
                        {{ csrf_field() }}
                </div>
                <div class="modal-body">
                    هل انت متاكد من عملية الارشفة ؟
                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    <input type="hidden" name="id_page" id="id_page" value="2">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                    <button type="submit" class="btn btn-success">تاكيد</button>
                </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>

<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>

<script>
	$('#delete_invoice').on('show.bs.modal' , function(event)
	{
		var button = $(event.relatedTarget) 
		var invoice_id = button.data('invoice_id')
		var modal = $(this) 
		modal.find('.modal-body #invoice_id').val(invoice_id) ;
	}) 

	$('#Transfer_invoice').on('show.bs.modal' , function(event)
	{
		var button = $(event.relatedTarget) 
		var invoice_id = button.data('invoice_id')
		var modal = $(this) 
		modal.find('.modal-body #invoice_id').val(invoice_id) ;
	}) 
</script>

@endsection