<?php 
ob_start();
$title = "About Us";
?>
<div class="about-container">
    <!-- English Section -->
    <section class="about-section about-en">
      <h2>About Us</h2>
      <p>Welcome to I.T.T Group of Education, where we provide quality education and coaching to help you achieve your academic goals. Our institute has a rich history of nurturing talented students with a dedicated team of experienced educators.</p>
      <h3>Our Vision</h3>
      <p>To empower every student with the knowledge, skills, and confidence to excel academically and in life.</p>
      <h3>Our Mission</h3>
      <p>We strive to create an engaging and supportive learning environment that fosters academic excellence, critical thinking, and personal growth.</p>
      <h3>Our History</h3>
      <p>Established in 2018, we have rapidly grown into a leading institute known for innovative teaching methods and comprehensive coaching programs.</p>
      <h3>Our Approach</h3>
      <p>We combine traditional teaching with modern technology, offering a well-rounded learning experience.</p>
    </section>
    <!-- Hindi Section -->
    <section class="about-section about-hi">
      <h2>हमारे बारे में</h2>
      <p>आई.टी.टी समूह ऑफ एजुकेशन में आपका स्वागत है, जहाँ हम गुणवत्तापूर्ण शिक्षा और कोचिंग प्रदान करते हैं ताकि आप अपने शैक्षिक लक्ष्यों को प्राप्त कर सकें। हमारे संस्थान का इतिहास प्रतिभाशाली छात्रों के विकास और अनुभवी शिक्षकों की समर्पित टीम के लिए प्रसिद्ध है।</p>
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


