<?php
    class PDF extends TCPDF {
        protected $last_page_flag = false;

        public function Close() {
            $this->last_page_flag = true;
            parent::Close();
        }

        //Page header
        public function Header(){
            $this->SetY(10);
            $mobile = '';
            foreach(explode(",",MOBILE_NO) as $key=>$val){
                if($val!=''){
                    $mobile .= $val.'<br/>';
                }
            }
            $body = '';
            $body .= '<table cellspacing="0" cellpadding="5" border="0" width="100%" style="border:  1px solid #ddd;">
                <tr nobr="true">
                    <td width="43%" align="left">GSTIN: '.GST_NO.'<br />PAN No: '.PAN_NO.'</td>
                    <td width="37%" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|| Shree ||<br />CASH / DEBIT MEMO</td>
                    <td width="20%" align="right">Mo.: '.$mobile.'</td>
                </tr>
                <tr nobr="true">
                    <td colspan="2"><h1>'.SITE_NAME.'</h1><p>'.ADDRESS.'</p></td>
                    <td valign="bottom">'.INFO.'</td>
                </tr>
            </table>';
            // Page number
            $this->Cell(100, 100, $this->writeHTML($body), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $this->SetY(50);
        }
    
        // Page footer
        public function Footer(){
            // Position at 15 mm from bottom
            
            $this->SetY(1);
            $disclaimer = '';
            foreach(explode(",",INVOICE_DISCLAIMER) as $key=>$val){
                if($val!=''){
                    $disclaimer .= ($key+1).') '.$val.'<br/>';
                }
            }
            $body = '';
            $body .= '<table nobr="true" cellspacing="0" cellpadding="5" border="0" width="100%" style="border-top: none;">
                        <tr>
                            <td width="50%"></td>
                            <td width="50%" align="right"><h2>For, '.SITE_NAME.'</h2></td>
                        </tr>
                        <tr>
                            <td>Truck No.:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>'.$disclaimer.'</td>
                            <td></td>
                        </tr>
                        <tr align="right">
                            <td></td>
                            <td>Authorised Signature</td>
                        </tr>
                    </table>';
                    
            if ($this->last_page_flag) {
                $this->SetY(-50);
                
                //$this->Cell(100, 100, $this->writeHTML($body), 0, false, 'C', 0, '', 0, false, 'T', 'M');
                //$this->Cell(0, 0,$this->writeHTML($body).'');
                $this->writeHTML($body, false, 0, false, 0);
                // Set font
                $this->Cell(1);
                $this->SetFont('helvetica', '', 8);
                $this->Cell(1);
                $this->Cell(0, 10,'Printed On:   '.date('d-M-y h:i:s A').'', 0, false, 'L', 0, '', 0, false, 'T', 'M');
                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
            }
            else{
                $this->SetY(-15);
                // Set font
                $this->SetFont('helvetica', '', 8);
                $this->Cell(1);
                $this->Cell(0, 10,'Printed On:   '.date('d-M-y h:i:s A').'', 0, false, 'L', 0, '', 0, false, 'T', 'M');
                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
            }
        }
    }
?>