<?php include('connect.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Water Tracker</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Daily Water Tracker</h2>
        <form id="waterForm">

            <label for="waterIntake">Glasses Intake:</label>
            <input type="number" id="waterIntake" name="waterIntake" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <button type="submit">Add Glasses</button>
        </form>

        <h3>Water Intake Graph</h3>
        <canvas id="waterChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('dailywaterintakebackend.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const intakes = data.intakes;  // Fetch the water intake data
                        const labels = intakes.map(intake => intake.date);
                        const amounts = intakes.map(intake => intake.amount);  // Corrected key to 'amount'

                        // Update the bar graph
                        const ctx = document.getElementById('waterChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Water Intake (Glasses)',
                                    data: amounts,  // Data representing water intake
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
                        console.error('Error fetching water intake data:', data.error);
                    }
                })
                .catch(error => console.error('Fetch error:', error));
        });

        // Handle Form Submission
        document.getElementById('waterForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Gather input data
            const waterIntake = document.getElementById('waterIntake').value;
            const date = document.getElementById('date').value;

            // Post data to server-side PHP
            fetch('dailywaterintakebackend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    waterIntake,
                    date
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Water intake added successfully!');
                    location.reload(); // Reload the page to update the graph with the new data
                } else {
                    alert('Failed to add water intake: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
