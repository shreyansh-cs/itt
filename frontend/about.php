<?php 
include_once 'session.php';
ob_start();
$title = "About Us";
?>
<div class="about">
    <section class="about-section">
      <p>आई.टी.टी समूह ऑफ एजुकेशन में आपका स्वागत है, जहाँ हम गुणवत्तापूर्ण शिक्षा और कोचिंग प्रदान करते हैं ताकि आप अपने शैक्षिक लक्ष्यों को प्राप्त कर सकें। </p>
      <p> हमारे संस्थान का इतिहास प्रतिभाशाली छात्रों के विकास और अनुभवी शिक्षकों की समर्पित टीम के लिए प्रसिद्ध है।</p>
      <h3>हमारा दृष्टिकोण</h3>
      <p>हर छात्र को ज्ञान, कौशल और आत्मविश्वास से सशक्त बनाना ताकि वे शैक्षणिक और जीवन में उत्कृष्टता प्राप्त कर सकें।</p>
      <h3>हमारा मिशन</h3>
      <p>हम एक आकर्षक और सहायक शिक्षण वातावरण प्रदान करते हैं, जो शैक्षणिक उत्कृष्टता, आलोचनात्मक सोच और व्यक्तिगत विकास को बढ़ावा देता है।</p>
      <h3>हमारा इतिहास</h3>
      <p>2018 में स्थापित, हमने तेजी से एक प्रमुख संस्थान के रूप में विकास किया है, जो नवोन्मेषी शिक्षण विधियों के लिए जाना जाता है।</p>
      <h3>हमारी कार्यप्रणाली</h3>
      <p>हम पारंपरिक शिक्षण को आधुनिक तकनीक के साथ मिलाकर एक समग्र शिक्षण अनुभव प्रदान करते हैं।</p>
    </section>
  </div>

  <?php 
  $content = ob_get_contents();
  ob_end_clean();
  require_once 'master.php'
  ?>


