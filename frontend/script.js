// script.js

function updateSubjects() {
    var classSelect = document.getElementById("class");
    var subjectSelect = document.getElementById("subject");
    
    subjectSelect.innerHTML = "";
    
    if (classSelect.value === "computer") {
      var computerSubjects = [
        { value: "fundamental", text: "Fundamental" },
        { value: "paint", text: "Paint" },
        { value: "notepad", text: "Notepad" },
        { value: "wordpad", text: "Wordpad" },
        { value: "ms-word", text: "MS Word" }
      ];
      computerSubjects.forEach(function(subject) {
        var option = document.createElement("option");
        option.value = subject.value;
        option.text = subject.text;
        subjectSelect.appendChild(option);
      });
    } else {
      var defaultSubjects = {
        "6": ["Political Science", "Hindi", "English", "Sanskrit", "Mathematics", "Science", "History", "Geography"],
        "7": ["Hindi", "English", "Sanskrit", "Mathematics", "Science", "History", "Geography", "Political Science"],
        "8": ["Hindi", "English", "Sanskrit", "Mathematics", "Science", "History", "Geography", "Political Science"],
        "9": ["Hindi", "English", "Mathematics", "Science", "Social Science"],
        "10": ["Hindi", "English", "Mathematics", "Science", "Social Science"],
        "11": ["Hindi", "English", "Mathematics", "Science", "Social Science"],
        "12": ["Hindi", "English", "Mathematics", "Science", "Social Science"]
      };
      (defaultSubjects[classSelect.value] || []).forEach(function(subject) {
        var option = document.createElement("option");
        option.value = subject.toLowerCase().replace(/\s+/g, "-");
        option.text = subject;
        subjectSelect.appendChild(option);
      });
    }
    
    updateChapters();
  }
  
  function updateChapters() {
    var classSelect = document.getElementById("class");
    var subjectSelect = document.getElementById("subject");
    var chapterSelect = document.getElementById("chapter");
    chapterSelect.innerHTML = "";
    
    var chapters = {
      science: [
        "Chapter 1 भोजन कहाँ से आता है?",
        "Chapter 2 भोजन में क्या-क्या है?",
        "Chapter 3 तन्तु से वस्त्र तक",
        "Chapter 4 विभिन्न प्रकार के पदार्थ",
        "Chapter 5 पृथक्करण",
        "Chapter 6 पदार्थों में परिवर्तन",
        "Chapter 7 पेड़-पौधों की दुनिया",
        "Chapter 8 फूलों से जान-पहचान",
        "Chapter 9 जन्तुओं में गति",
        "Chapter 10 सजीव और निर्जीव",
        "Chapter 11 सजीवों में अनुकूलन",
        "Chapter 12 दूरी, मापन एवं गति",
        "Chapter 13 प्रकाश",
        "Chapter 14 बल्ब जलाओ जगमग-जगमग",
        "Chapter 15 चुम्बक",
        "Chapter 16 जल",
        "Chapter 17 वायु",
        "Chapter 18 ठोस कचरा प्रबंधन"
      ],
      // अन्य विषयों के लिए भी अध्यायों की सूची यहाँ होगी
      mathematics: [
        "Chapter 1 वास्तविक संख्याएँ",
        "Chapter 2 बहुपद",
        "Chapter 3 दो चरों वाले रैखिक समीकरण युग्म",
        "Chapter 4 द्विघात समीकरण",
        "Chapter 5 समांतर श्रेढ़ियाँ",
        "Chapter 6 त्रिभुज",
        "Chapter 7 निर्देशांक ज्यामिति",
        "Chapter 8 त्रिकोणमिति का परिचय",
        "Chapter 9 त्रिकोणमिति के कुछ अनुप्रयोग",
        "Chapter 10 वृत्त",
        "Chapter 11 रचनाएँ",
        "Chapter 12 वृतों से संबंधित क्षेत्रफल",
        "Chapter 13 पृष्ठीय क्षेत्रफल एवं आयतन",
        "Chapter 14 सांख्यिकी",
        "Chapter 15 प्रायिकता"
      ]
    };
    
    // Specific overrides (उदाहरण: कक्षा 6 के लिए)
    // ...
    
    if (chapters[subjectSelect.value]) {
      chapters[subjectSelect.value].forEach(function(chapter) {
        var option = document.createElement("option");
        option.value = chapter;
        option.text = chapter;
        chapterSelect.appendChild(option);
      });
    } else {
      var option = document.createElement("option");
      option.value = "none";
      option.text = "No chapters available";
      chapterSelect.appendChild(option);
    }
  }
  
  function viewResults() {
    alert("Result viewing feature coming soon!");
  }
  
  window.onload = updateSubjects;
  
  