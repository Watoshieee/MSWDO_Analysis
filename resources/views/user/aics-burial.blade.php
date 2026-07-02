@php
    $aicsStorageKey = 'aicsBurial';
    $aicsProgramType = 'AICS_Burial';
    $aicsProgramLabel = 'AICS Burial Assistance';
    $aicsPageTitle = 'AICS Burial Assistance - MSWDO';
    $aicsTitleEn = 'Burial Assistance';
    $aicsTitleTl = 'Tulong sa Libing';
    $aicsBatchRoute = route('user.aics-burial-upload-batch');
    $aicsSingleRoute = route('user.aics-burial-upload');
    $aicsBackRoute = route('user.aics-category');
    $aicsGuideSteps = [
        ['en'=>'Schedule an Appointment','tl'=>'Mag-schedule ng Appointment','descEn'=>'Book a face-to-face or online interview with the MSWDO for burial assistance.','descTl'=>'Mag-book ng harapan o online na panayam sa MSWDO para sa tulong sa libing.'],
        ['en'=>'Wait for Confirmation','tl'=>'Hintayin ang Kumpirmasyon','descEn'=>'The admin will review and confirm your appointment via website and email.','descTl'=>'Susuriin at kukumpirmahin ng admin ang iyong appointment sa website at email.'],
        ['en'=>'Attend the Interview','tl'=>'Dumalo sa Panayam','descEn'=>'Go to the MSWDO office or attend your scheduled online interview.','descTl'=>'Pumunta sa opisina ng MSWDO o dumalo sa online na panayam.'],
        ['en'=>'Eligibility Assessment','tl'=>'Eligibility Assessment','descEn'=>'MSWDO will assess your eligibility for burial financial assistance.','descTl'=>'Susuriin ng MSWDO ang iyong eligibility para sa tulong sa libing.'],
        ['en'=>'Submit Requirements','tl'=>'Isumite ang mga Requirements','descEn'=>'If eligible, upload all required documents including death certificate and indigency certificate.','descTl'=>'Kung karapat-dapat, i-upload ang lahat ng kinakailangang dokumento kabilang ang death certificate at certificate of indigency.'],
        ['en'=>'Receive Assistance','tl'=>'Tumanggap ng Tulong','descEn'=>'Once approved, MSWDO will process your burial assistance.','descTl'=>'Kapag naaprubahan, ipoproseso ng MSWDO ang iyong tulong sa libing.'],
    ];
@endphp
@include('user.partials.aics-application')
