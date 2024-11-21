import pandas as pd
import pymysql
from statsmodels.tsa.arima.model import ARIMA
import json
import sys

# Database connection parameters from config.php
DB_HOST = "localhost"
DB_USER = "root"  # Replace with your MySQL user
DB_PASSWORD = ""  # Replace with your MySQL password
DB_NAME = "drolah"

# Connect to MySQL
connection = pymysql.connect(
    host=DB_HOST,
    user=DB_USER,
    password=DB_PASSWORD,
    database=DB_NAME
)

# Fetch sales data (grouped by month)
query = """
    SELECT 
        DATE_FORMAT(sales_date, '%Y-%m') AS sales_month, 
        SUM(quantity) AS total_quantity
    FROM sales_details
    GROUP BY sales_month
    ORDER BY sales_month
"""
data = pd.read_sql(query, connection)
connection.close()

# Convert sales_month to datetime
data['sales_month'] = pd.to_datetime(data['sales_month'])
data.set_index('sales_month', inplace=True)

# Forecast demand for the next 3 months
model = ARIMA(data['total_quantity'], order=(1, 1, 1))  # Adjust ARIMA parameters as needed
model_fit = model.fit()

# Generate forecast
forecast_duration = int(sys.argv[1])  # Get forecast duration from PHP (e.g., 3 for 3 months)
forecast = model_fit.forecast(steps=forecast_duration)
forecast_dates = pd.date_range(start=data.index[-1], periods=forecast_duration + 1, freq='M')[1:]

# Prepare output
forecast_result = {"forecast": [{"Date": str(d), "Forecast": float(f)} for d, f in zip(forecast_dates, forecast)]}
historical_data = data.reset_index().to_dict(orient='records')

# Output JSON
result = {
    "historical": historical_data,
    "forecast": forecast_result
}
print(json.dumps(result))
