<?php include('connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Calorie Intake Tracker</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Daily Calorie Intake Tracker</h2>
        <form id="calorieForm">
            <label for="mealName">Meal Name:</label>
            <input type="text" id="mealName" name="mealName" required>

            <label for="mealDate">Meal Date:</label>
            <input type="date" id="mealDate" name="mealDate" required>

            <label for="calories">Calories:</label>
            <input type="number" id="calories" name="calories" required>

            <label for="mealDescription">Meal Description:</label>
            <textarea id="mealDescription" name="mealDescription" required></textarea>

            <button type="submit">Add Meal</button>
        </form>

        <h3>Calorie Intake Graph</h3>
        <canvas id="calorieChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('dailycalorieintakebackend.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const meals = data.meals;
                        const labels = meals.map(meal => meal.date);
                        const calories = meals.map(meal => meal.calories);

                        // Update the bar graph
                        const ctx = document.getElementById('calorieChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Calories Intake',
                                    data: calories,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: { beginAtZero: true }
                                }
                            }
                        });
                    } else {
                        console.error('Error fetching meals:', data.error);
                    }
                })
                .catch(error => console.error('Fetch error:', error));
        });

        // Handle Form Submission
        document.getElementById('calorieForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Gather input data
            const mealName = document.getElementById('mealName').value;
            const mealDate = document.getElementById('mealDate').value;
            const calories = document.getElementById('calories').value;
            const mealDescription = document.getElementById('mealDescription').value;

            // Post data to server-side PHP
            fetch('dailycalorieintakebackend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    mealName,
                    mealDate,
                    calories,
                    mealDescription
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Meal added successfully!');
                    location.reload(); // Reload the page to update the graph with the new data
                } else {
                    alert('Failed to add meal: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
