<?php

	namespace App\Http\Controllers\api;

	use App\Http\Controllers\OrderServices;
	use App\Order;
	use Illuminate\Http\Request;

	class OrderController extends OrderServices
	{


		/**
		 * Display a listing of the resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function index ()
		{
			return $this->getAllOrder ();
		}

		/**
		 * Show the form for creating a new resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function create ()
		{
			//
		}

		/**
		 * Store a newly created resource in storage.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @return \Illuminate\Http\Response
		 */
		public function store (Request $request)
		{
			return $this->create_order ( $request );
		}

		/**
		 * Display the specified resource.
		 *
		 * @param  \App\Order $order
		 * @return \Illuminate\Http\Response
		 */
		public function show ($id)
		{
			return $this->getOneOrder ( $id );
		}

		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param  \App\Order $order
		 * @return \Illuminate\Http\Response
		 */
		public function edit (Order $order)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \App\Order $order
		 * @return \Illuminate\Http\Response
		 */
		public function update (Request $request , $id)
		{
			return $this->update_order ( $request , $id );

		}

		/**
		 * Remove the specified resource from storage.
		 *
		 * @param  \App\Order $order
		 * @return \Illuminate\Http\Response
		 */
		public function destroy ($id)
		{
			return $this->delete_order ( $id );
		}
	}
