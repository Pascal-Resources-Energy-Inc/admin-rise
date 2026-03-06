<div class="modal fade" id="contractView" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contractModalLabel"><i class="bi bi-file-earmark-text"></i> Contract Viewing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
            <table class="table table-bordered" border="1">
                <tr>
                    <th colspan="2">GAZ LITE PILOT PARTICIPATION AGREEMENT FORM</th>
                    <th>Date: {{date('M d,Y',strtotime($customer->created_at))}}</th>
                    <th>Serial Number: {{$customer->serial->serial_number ?? ''}}</th>
                </tr>
                <tr>
                    <td colspan=4>Name of Customer: {{$customer->name}}</td>
                </tr>
                 <tr>
                    <td colspan=4>Address: {{ implode(', ', array_filter([
                                $customer->street_address,
                                $customer->location_barangay,
                                $customer->location_city,
                                $customer->location_province
                            ])) }} {{ $customer->postal_code }}
                    </td>
                </tr>
                 <tr>
                    <td colspan=4>Contact No.: {{$customer->number}}</td>
                </tr>
                {{-- <tr>
                    <td colspan=4 style='background-color:gray;'>KASUNDUAN SA PAKIKIBAHAGI SA PROYEKTO NG CARBON CREDIT GAZ LITE FEASIBILITY STUDY</td>
                </tr> --}}
            </table>
            <div class="form-section">
            <p>
                Ito ay nagpapatunay na ako, <b><u>{{$customer->name}}</b></u>, ay kusang-loob na pumapayag na makibahagi sa Project Rise (Reaching Inclusivity with Sustainable Energy) ng Gaz Lite at sumasang-ayon na sundin ang mga sumusunod na patakaran at alituntunin ng proyekto.<br>
              <small class="text-muted">(This certifies that I, <b><u>{{$customer->name}}</b></u>, voluntarily agree to participate in the Project RISE (Reaching Inclusivity with Sustainable Energy) of Gaz Lite and agree to comply with the following project rules and guidelines.)</small>
            </p>
            <h6 class="mt-4"><strong>Mga panuntunan at kasunduan ng kalahok:</strong></h6>
            <ol>
              <li>
                Paggamit ng Malinis na Panggatong<br>
                Ako ay kusang-loob na gagamit ng Gaz Lite LPG cookstove at Gaz Lite 330g micro-cylinder refills bilang pangunahing panggatong sa pagluluto sa aming tahanan bilang kapalit ng kahoy at/o uling. Nauunawaan ko na ang paggamit ng mas malinis na panggatong ay makatutulong sa pagbawas ng mga panganib sa kalusugan ng aking pamilya at sa masamang epekto sa kapaligiran. Nauunawaan ko rin na sa paggamit ng hindi bababa sa walong (8) Gaz Lite 330g refills bawat buwan, ang aming kasalukuyang gastusin sa panggatong na umaabot sa humigit-kumulang Php 1,800 bawat buwan ay maaaring mabawasan ng hanggang 70%, o bumaba sa tinatayang Php 520 bawat buwan.
                <br><small class="text-muted">(I voluntarily agree to use the Gaz Lite LPG cookstove and Gaz Lite 330g micro-cylinder refills as the primary cooking fuel in our household in place of firewood and/or charcoal. I understand that the use of cleaner cooking fuel helps reduce health risks to my family and harmful environmental impacts. I further understand that by using at least eight (8) Gaz Lite 330g refills per month, our current household fuel expenses of approximately Php 1,800 per month may be reduced by as much as 70%, bringing the estimated monthly cost down to about Php 520.)</small>
              </li>

              <li class="mt-3">
                Pang-araw-araw na Paggamit sa Loob ng Sampung (10) Taon<br>
                Ako ay sumasang-ayon na gagamit ng Gaz Lite LPG cookstove at Gaz Lite 330g micro-cylinder refills araw-araw sa paghahanda ng pagkain sa aming tahanan sa buong panahon ng proyekto na tumatagal ng sampung (10) taon, upang mapalitan o makabuluhang mabawasan ang paggamit ng kahoy at/o uling sa pagluluto.
                <br><small class="text-muted">(I agree to use the Gaz Lite LPG cookstove and Gaz Lite 330g micro-cylinder refills daily in preparing meals in our household throughout the ten (10) year duration of the project in order to replace or significantly reduce the use of firewood and/or charcoal.)</small>
              </li>

              <li class="mt-3">
                Eksklusibong Paggamit ng Gaz Lite<br>
                Ako ay sumasang-ayon na hindi gagamit ng anumang kahalintulad o kaparehong produkto maliban sa Gaz Lite habang ako ay kalahok sa proyekto. Ang paglabag sa patakarang ito ay maaaring magresulta sa aking pagtanggal sa proyekto at sa pagsasauli ng mga produktong Gaz Lite na ipinagkaloob sa akin.
                <br><small class="text-muted">(I agree not to use any similar or equivalent product other than Gaz Lite while participating in the project. Violation of this condition may result in my removal from the project and the return of the Gaz Lite products issued to me.)</small>
              </li>

              <li class="mt-3">
                Minimum na Konsumo ng Refill<br>
                Ako ay sumasang-ayon na gumamit ng hindi bababa sa walong (8) Gaz Lite 330g refills bawat buwan. Ang hindi pagsunod sa patakarang ito ay ituturing na paglabag sa kasunduang ito at maaaring magresulta sa pagsasauli ng produktong ipinagkaloob sa akin. Kung hindi ko maibalik ang produkto, ako ay sumasang-ayon na bayaran ito sa halagang Php 1,034.00.
                <br><small class="text-muted">(I agree to purchase and use no less than eight (8) Gaz Lite 330g refills per month. Failure to comply with this requirement shall constitute a violation of this agreement and may result in the return of the product issued to me. If the product cannot be returned, I agree to pay the amount of Php 1,034.00.)</small>
              </li>
              {{-- 5 --}}
              <li class="mt-3">
                Tagal ng Paglahok sa Proyekto<br>
                Nauunawaan ko na ang proyektong ito ay may tagal na sampung (10) taon, at ako ay sumasang-ayon na sumunod sa mga patakaran nito sa buong panahon ng aking paglahok.
                <br><small class="text-muted">(I understand that this project has a duration of ten (10) years and I agree to comply with its guidelines for the duration of my participation.)</small>
              </li>
              {{-- 6 --}}
              <li class="mt-3">
                Pagprotekta sa Personal na Impormasyon<br>
                Ako ay sumasang-ayon na ang aking personal na impormasyon at datos ng paggamit ng cookstove ay maaaring kolektahin at iproseso para sa layunin ng pamamahala, pagsubaybay, pagsusuri, at pag-uulat ng proyekto. Ang lahat ng impormasyon ay pangangalagaan alinsunod sa Data Privacy Act of 2012 at iba pang naaangkop na batas sa Pilipinas.
                <br><small class="text-muted">(I agree that my personal information and stove usage data may be collected and processed for purposes of project management, monitoring, evaluation and reporting in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173) and other applicable Philippine laws.)</small>
              </li>
              {{-- 7 --}}
              <li class="mt-3">
                Kusang-Loob na Paglahok at May Kaalamang Pahintulot<br>
                Ako ay nagpapatunay na ang aking paglahok sa proyekto ay kusang-loob, at ako ay nabigyan ng sapat na paliwanag tungkol sa layunin, benepisyo, at mga responsibilidad ng proyekto. 
                <br><small class="text-muted">(I certify that my participation in this project is voluntary and that I have been adequately informed of the project’s objectives, benefits, and responsibilities.)</small>
              </li>
              {{-- 8 --}}
              <li class="mt-3">
                Pagsubaybay at Pagpapatunay ng Proyekto<br>
                Ako ay sumasang-ayon na pahintulutan ang mga kinatawan ng proyekto na magsagawa ng monitoring, pagbisita, o pangangalap ng impormasyon tungkol sa paggamit ng Gaz Lite cookstove at refills para sa layunin ng pagsusuri, pag-uulat, at pagpapatunay ng proyekto. 
                <br><small class="text-muted">(I agree to allow project representatives to conduct monitoring visits and information gathering regarding the use of the Gaz Lite cookstove and Gaz Lite 330g micro-cylinder refills for purposes of project evaluation, reporting and verification.)</small>
              </li>
            </ol>

            {{-- <div class="signature-box mt-5">
                Lagda / Pirma (Signature)
            </div> --}}
            </div>
     

          <!-- Signature Pad -->
          <div class="mb-3 text-right">
             <img src="{{ asset($customer->signature)}}" alt="Valid ID" class="img-fluid rounded shadow-sm" style="max-height: 250px;" />
           
          </div>

          <!-- Hidden input to store signature -->
          <input type="hidden" name="contract_signature" id="contract_signature" />

        </div>
         <div class="modal-footer">
        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
          data-bs-dismiss="modal">
          Close
        </button>
      </div>
      </div>
  </div>
</div>