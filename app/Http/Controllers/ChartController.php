 public function preview()
    {
        return view('chart');
    }
  
    /**
     * Write code on Construct
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $render = view('chart')->render();
  
        $pdf = new Pdf;
        $pdf->addPage($render);
        $pdf->setOptions(['javascript-delay' => 5000]);
        $pdf->saveAs(public_path('report.pdf'));
   
        return response()->download(public_path('report.pdf'));
    }
}