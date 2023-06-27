function loginUser() {
    // Récupérer les valeurs des champs email et password
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
  
    // Effectuer les vérifications des données en front-end
    if (email === "" || password === "") {
      document.getElementById('error-message').innerText = "Veuillez remplir tous les champs";
      return;
    }
  
    // Désactiver le bouton de connexion pour éviter les requêtes multiples
    document.querySelector('#login-form button').disabled = true;
  
    // Envoyer les données de connexion à l'API via une requête AJAX
    $.ajax({
      url: "backend/api.php",
      method: "POST",
      data: {
        action: "login",
        email: email,
        password: password
      },
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          // Rediriger vers la page personnelle de l'utilisateur
          window.location.href = "page-personnelle.html";
        } else {
          // Afficher le message d'erreur renvoyé par l'API
          document.getElementById('error-message').innerText = response.message;
          // Réactiver le bouton de connexion
          document.querySelector('#login-form button').disabled = false;
        }
      },
      error: function(error) {
        // Gérer les erreurs de la requête AJAX
        console.log(error);
        // Réactiver le bouton de connexion
        document.querySelector('#login-form button').disabled = false;
      }
    });
  }
  
// Global variables
let selectedHorse = null; // Track the selected horse
let raceResult = null; // Store the race result

// Function to create the horse elements dynamically
function createHorses() {
  const horseContainer = document.getElementById("horseContainer");
  for (let i = 1; i <= 8; i++) {
    const horse = document.createElement("div");
    horse.className = "horse";
    horse.textContent = i; // Use numbers as horse representation
    horseContainer.appendChild(horse);
    horse.addEventListener("click", horseClickHandler); // Attach click event listener to each horse
  }
}

// Event handler for horse click
function horseClickHandler() {
  // Deselect previously selected horse
  if (selectedHorse !== null) {
    selectedHorse.classList.remove("selected");
  }

  // Select the clicked horse
  if (selectedHorse !== this) {
    this.classList.add("selected");
    selectedHorse = this;
  } else {
    selectedHorse = null;
  }
}

// Event handler for bet confirmation
function confirmBetHandler() {
  const betAmount = parseInt(document.getElementById("betAmount").value);
  if (betAmount >= 0 && betAmount <= 100) {
    // Send the bet amount and selected horse to the backend
    // Perform validation, processing, and response handling on the backend
    // Update the frontend with the race result and handle further actions
    // (e.g., displaying the race animation, updating the account balance, etc.)
    console.log("Bet Amount:", betAmount);
    console.log("Selected Horse:", selectedHorse.textContent);

    // Simulate the race result
    const randomPosition = Math.floor(Math.random() * 8) + 1;
    raceResult = randomPosition === parseInt(selectedHorse.textContent);

    // Display the race result notification
    const messageElement = document.getElementById("message");
    if (raceResult) {
      messageElement.textContent = "Congratulations! You won the race!";
    } else {
      messageElement.textContent = "Oops! Better luck next time.";
    }

    // Show the message for a few seconds and reset the race
    setTimeout(() => {
      messageElement.textContent = "";
      resetRace();
    }, 3000);
  } else {
    alert("Please enter a valid bet amount (between 0 and 100).");
  }
}

// Function to reset the race
function resetRace() {
  const horses = document.getElementsByClassName("horse");
  for (let i = 0; i < horses.length; i++) {
    const horse = horses[i];
    horse.style.marginLeft = "0";
  }

  // Clear the selected horse
  if (selectedHorse !== null) {
    selectedHorse.classList.remove("selected");
    selectedHorse = null;
  }

  // Clear the bet amount
  document.getElementById("betAmount").value = "";
}

// Event handler for starting the race
function startRaceHandler() {
  // Disable the race button to prevent multiple clicks
  document.getElementById("startRaceBtn").disabled = true;

  // Slow down the race and adjust the race duration
  const raceDuration = 10000; // 10 seconds

  // Randomize horse speeds
  const speeds = [];
  for (let i = 0; i < 8; i++) {
    speeds[i] = Math.floor(Math.random() * 3) + 1; // Random speed between 1 and 3
  }

  // Race animation loop
  const startTime = Date.now();
  const raceInterval = setInterval(() => {
    const elapsedTime = Date.now() - startTime;
    const distance = window.innerWidth - 60; // Race distance based on window width

    // Update horse positions based on elapsed time and speeds
    for (let i = 0; i < horses.length; i++) {
      const horse = horses[i];
      const position = (elapsedTime / raceDuration) * distance * speeds[i];
      horse.style.marginLeft = position + "px";
    }

    // Check if the race is completed
    if (elapsedTime >= raceDuration) {
      clearInterval(raceInterval);
      document.getElementById("startRaceBtn").disabled = false;

      // Check if the race result is available
      if (raceResult !== null) {
        // Display the race result notification
        const messageElement = document.getElementById("message");
        if (raceResult) {
          messageElement.textContent = "Congratulations! You won the race!";
        } else {
          messageElement.textContent = "Oops! Better luck next time.";
        }

        // Show the message for a few seconds and reset the race
        setTimeout(() => {
          messageElement.textContent = "";
          resetRace();
        }, 3000);
      }
    }
  }, 50); // Adjust the interval for smoother animation
}

// Attach event listeners
document.addEventListener("DOMContentLoaded", createHorses);

const horses = document.getElementsByClassName("horse");

document.getElementById("confirmBetBtn").addEventListener("click", confirmBetHandler);
document.getElementById("startRaceBtn").addEventListener("click", startRaceHandler);
