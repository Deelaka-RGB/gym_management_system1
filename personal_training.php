<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Personal Training</title>
  <style>
    :root {
      --primary: #1e90ff;
      --primary-hover: #0066cc;
      --accent-shadow: rgba(30, 144, 255, 0.3);
      --bg-light: #f0f4f8;
      --card-bg: #ffffff;
      --text: #1a1a1a;
      --header-bg: #0b2545; /* Dark Blue */
      --header-text: #ffffff;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--bg-light);
      margin: 0;
      padding: 0;
      color: var(--text);
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      background: var(--card-bg);
      padding: 30px;
      border-radius: 14px;
      box-shadow: 0 8px 24px rgba(3, 84, 165, 0.15);
    }

    header {
      background-color: var(--header-bg);
      color: var(--header-text);
      padding: 20px 30px;
      border-radius: 14px 14px 0 0;
      margin: -30px -30px 30px -30px;
      text-align: center;
      font-size: 2.8em;
      font-weight: 700;
      font-family: 'Segoe UI Black', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-home {
      display: inline-block;
      margin-bottom: 25px;
      padding: 10px 22px;
      background-color: var(--primary);
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .btn-home:hover {
      background-color: var(--primary-hover);
    }

    .grid {
      display: grid;
      grid-template-columns: 2;
      gap: 50px;
    }

    .card {
      background: var(--card-bg);
      background-color:lightblue;
      border-radius: 14px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 6px 20px var(--accent-shadow);
      transition: all 0.3s ease;
      cursor: default;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px var(--accent-shadow);
      cursor: pointer;
    }

    .card h2 {
      font-size: 22px;
      color: black
      margin-bottom: 15px;
    }

    .card p {
      color: #555;
      font-size: 20px;
      margin-bottom: 20px;
      font-weight:16px;
    }

    .view-btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: var(--primary);
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .view-btn:hover {
      background-color: var(--primary-hover);
    }

    @media (max-width: 600px) {
      header {
        font-size: 2em;
      }

      .card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <header>Personal Training Options</header>

  <a href="member_dashboard.php" class="btn-home">🏠 Home</a>

  <div class="grid">
    <!-- Book Personal Training -->
    <div class="card">
      <h2>📅 Book Personal Training</h2>
      <p>Choose your trainer and time slot for a focused training session.</p>
      <a href="book_training.php" class="view-btn">Book Now</a>
    </div>

    <!-- Request Diet Plan -->
    <div class="card">
      <h2>🥗 Request Diet Plan</h2>
      <p>Let your trainer help you eat smarter and train harder.</p>
      <a href="request_diet_plan.php" class="view-btn">Request Plan</a>
    </div>

    <!-- Contact Trainer -->
    <div class="card">
      <h2>📞 Contact Trainer</h2>
      <p>Reach out directly to your trainer for questions or advice.</p>
      <a href="contact_trainer.php" class="view-btn">Contact</a>
    </div>

    <!-- Rate Your Trainer -->
    <div class="card">
      <h2>⭐ Rate Your Trainer</h2>
      <p>Give feedback and rate your trainer to help us improve.</p>
      <a href="rate_trainer.php" class="view-btn">Rate Now</a>
    </div>
  </div>
</div>

</body>
</html>
