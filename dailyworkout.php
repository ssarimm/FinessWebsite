<?php include('connect.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Workout Tracker</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Daily Workout Tracker</h2>
        <form id="workoutForm">
            <label for="workoutName">Workout Name:</label>
            <input type="text" id="workoutName" name="workoutName" required>

            <label for="workoutDate">Workout Date:</label>
            <input type="date" id="workoutDate" name="workoutDate" required>

            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime" required>

            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime" required>

            <label for="duration">Workout Duration (minutes):</label>
            <input type="number" id="duration" name="duration" required>

            <label for="caloriesBurned">Calories Burned:</label>
            <input type="number" id="caloriesBurned" name="caloriesBurned" required>

            <button type="submit">Add Workout</button>
        </form>

        <h3>Calorie Burn Graph</h3>
        <canvas id="calorieChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('dailyworkoutbackend.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const workouts = data.workouts;
                        const labels = workouts.map(workout => workout.date);
                        const calories = workouts.map(workout => workout.caloriesBurned);

                        // Update the bar graph
                        const ctx = document.getElementById('calorieChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Calories Burned',
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
                        console.error('Error fetching workouts:', data.error);
                    }
                })
                .catch(error => console.error('Fetch error:', error));
        });

        // Handle Form Submission
        document.getElementById('workoutForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Gather input data
            const workoutName = document.getElementById('workoutName').value;
            const workoutDate = document.getElementById('workoutDate').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const duration = document.getElementById('duration').value;
            const caloriesBurned = document.getElementById('caloriesBurned').value;

            // Post data to server-side PHP
            fetch('dailyworkoutbackend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    workoutName,
                    workoutDate,
                    startTime,
                    endTime,
                    duration,
                    caloriesBurned
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Workout added successfully!');
                    location.reload(); // Reload the page to update the graph with the new data
                } else {
                    alert('Failed to add workout: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
