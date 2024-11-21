import pymysql
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from statsmodels.tsa.api import ExponentialSmoothing

# Database Connection Function
def connect_to_db():
    """
    Connect to the MySQL database using credentials from your config.php file.
    Update with your actual database settings.
    """
    db = pymysql.connect(
        host = 'localhost'; // Change if needed
        database   = 'drolah';
        user = 'root'; // Change if needed
        password = ''; // Change if needed

    )
    return db

# Fetch Sales Data from Database
def fetch_sales_data():
    """
    Fetch sales data from the database. Update the table name in the query.
    """
    db = connect_to_db()
    query = """
    SELECT 
        sales_id, product_id, quantity, subtotal, sales_date, name, discount_percent
    FROM sales_table;  -- Replace 'sales_table' with your actual table name
    """
    sales_data = pd.read_sql(query, db)
    db.close()
    return sales_data

# Descriptive Analysis
def descriptive_analysis(data):
    """
    Perform descriptive analysis on the sales data.
    """
    print("General Overview:")
    print(data.describe())
    print("\nTotal Sales:", data['subtotal'].sum())
    print("\nAverage Discount Applied:", data['discount_percent'].mean())
    print("\nMonthly Sales Trends:")
    monthly_sales = data.groupby(data['sales_date'].dt.to_period('M'))['subtotal'].sum()
    print(monthly_sales)
    return monthly_sales

# Sales Trend Analysis
def sales_trend_analysis(data):
    """
    Analyze and plot sales trends.
    """
    data['sales_date'] = pd.to_datetime(data['sales_date'])
    monthly_sales = data.groupby(data['sales_date'].dt.to_period('M'))['subtotal'].sum().reset_index()
    monthly_sales['sales_date'] = monthly_sales['sales_date'].dt.to_timestamp()
    
    plt.figure(figsize=(10, 6))
    plt.plot(monthly_sales['sales_date'], monthly_sales['subtotal'], marker='o', linestyle='-')
    plt.title("Monthly Sales Trends")
    plt.xlabel("Date")
    plt.ylabel("Total Sales")
    plt.grid()
    plt.show()

# Demand Forecasting
def demand_forecasting(data):
    """
    Forecast demand using Exponential Smoothing.
    """
    data['sales_date'] = pd.to_datetime(data['sales_date'])
    monthly_sales = data.groupby(data['sales_date'].dt.to_period('M'))['quantity'].sum()
    monthly_sales.index = monthly_sales.index.to_timestamp()
    
    # Fit the Exponential Smoothing model
    model = ExponentialSmoothing(monthly_sales, trend="add", seasonal="add", seasonal_periods=12)
    fit = model.fit()
    forecast = fit.forecast(steps=12)
    
    # Plot the forecast
    plt.figure(figsize=(10, 6))
    plt.plot(monthly_sales, label='Historical Sales')
    plt.plot(forecast, label='Forecasted Demand', linestyle='--')
    plt.title("Demand Forecasting")
    plt.xlabel("Date")
    plt.ylabel("Quantity")
    plt.legend()
    plt.grid()
    plt.show()

# Inventory Return Rate Calculation
def inventory_return_rate(data):
    """
    Calculate the inventory return rate.
    """
    total_sales = data['quantity'].sum()
    returns = data[data['quantity'] < 0]['quantity'].sum()  # Assuming negative quantities are returns
    return_rate = abs(returns) / total_sales * 100  # As a percentage
    print(f"Inventory Return Rate: {return_rate:.2f}%")
    return return_rate

# Main Function
def main():
    """
    Main function to perform all analyses.
    """
    sales_data = fetch_sales_data()
    
    # Convert date column
    sales_data['sales_date'] = pd.to_datetime(sales_data['sales_date'])
    
    print("\n--- Descriptive Analysis ---")
    descriptive_analysis(sales_data)
    
    print("\n--- Sales Trend Analysis ---")
    sales_trend_analysis(sales_data)
    
    print("\n--- Demand Forecasting ---")
    demand_forecasting(sales_data)
    
    print("\n--- Inventory Return Rate ---")
    inventory_return_rate(sales_data)

if __name__ == "__main__":
    main()
