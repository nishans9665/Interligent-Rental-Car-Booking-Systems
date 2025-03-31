import mysql.connector
import pandas as pd
import json
import sys
from sklearn.metrics.pairwise import cosine_similarity
from config import get_db_connection


db = get_db_connection()
cursor = db.cursor()


# Fetch booking data
cursor.execute("SELECT userEmail, VehicleId FROM tblbooking")
bookings = cursor.fetchall()
df = pd.DataFrame(bookings, columns=['userEmail', 'VehicleId'])

# Convert data into user-car matrix
user_car_matrix = df.pivot_table(index='userEmail', columns='VehicleId', aggfunc=len, fill_value=0)

# Compute similarity between cars
car_similarity = cosine_similarity(user_car_matrix.T)
car_similarity_df = pd.DataFrame(car_similarity, index=user_car_matrix.columns, columns=user_car_matrix.columns)

# Function to recommend cars
def recommend_cars(user_id):
    if user_id not in user_car_matrix.index:
        return []

    # Get the user's previously rented cars
    rented_cars = user_car_matrix.loc[user_id]
    rented_cars = rented_cars[rented_cars > 0].index.tolist()

    recommendations = {}
    for car in rented_cars:
        similar_cars = car_similarity_df[car].sort_values(ascending=False).drop(car)
        for similar_car, score in similar_cars.items():
            recommendations[similar_car] = recommendations.get(similar_car, 0) + score

    # Sort recommendations by score
    recommended_cars = sorted(recommendations.items(), key=lambda x: x[1], reverse=True)
    return [car for car, _ in recommended_cars[:5]]

# Example: Get recommendations for user 1
user_id = 1
recommended = recommend_cars(user_id)
print(recommended)

cursor.close()
db.close()
