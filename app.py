# -*- coding: utf-8 -*-
import sqlite3
import os
from flask import Flask, request, jsonify, render_template
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # Autorise les requêtes depuis d'autres domaines (CORS)

# Connexion à la base de données SQLite
DATABASE = 'bdd.db'

def get_db_connection():
    """
    Ouvre une connexion à la base de données SQLite.
    """
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row  # Permet d'accéder aux colonnes par leur nom
    return conn

@app.route('/')
def home():
    """
    Route d'accueil. Renvoie un fichier HTML si disponible.
    """
    if not os.path.exists('templates/index.html'):
        return "Fichier HTML introuvable dans le dossier 'templates'", 500
    return render_template('index.html')

@app.route('/search', methods=['GET'])
def search():
    """
    Route de recherche dans la base de données.
    """
    # Récupération du paramètre de recherche
    search = request.args.get('search', '').strip()
    results = []  # Liste des résultats
    count = 0  # Ajouté : Initialisation de la variable count

    if search:
        # Préparation des requêtes SQL
        query = """
            SELECT * FROM BDD 
            WHERE titre LIKE ? OR auteur LIKE ? OR paroles LIKE ? 
               OR compositeur LIKE ? OR annee LIKE ? OR duree LIKE ? OR album LIKE ?
        """  # Recherche complète
        county = """
            SELECT COUNT(*) FROM BDD 
            WHERE titre LIKE ? OR auteur LIKE ? OR paroles LIKE ? 
            OR compositeur LIKE ? OR annee LIKE ? OR duree LIKE ? OR album LIKE ?
        """  # Comptage des résultats

        # Création des paramètres de recherche avec les wildcards
        search_term = f"%{search}%"
        params = [search_term] * 7

        # Exécution des requêtes
        conn = get_db_connection()
        try:
            rows = conn.execute(query, params).fetchall()
            count = conn.execute(county, params).fetchone()[0]  # Récupération du nombre total de résultats
        finally:
            conn.close()  # Assurez-vous que la connexion est fermée

        # Conversion des résultats en liste de dictionnaires
        results = [
            {
                'titre': row['titre'],
                'album': row['album'],
                'annee': row['annee'],
                'duree': row['duree'],
                'paroles': row['paroles'],
                'compositeur': row['compositeur'],
                'auteur': row['auteur']
            }
            for row in rows
        ]

    # Retour des résultats au format JSON
    return jsonify({
        'count': count,  # Ajouté : Inclusion du nombre de résultats
        'searchTerm': search,
        'results': results
    })

if __name__ == "__main__":
    app.run(debug=True)