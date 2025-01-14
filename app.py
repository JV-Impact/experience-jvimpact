from flask import Flask, render_template, request, redirect, url_for, session, flash
import sqlite3
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = 'secret_key_experiance'

# Connexion à la base de données
def get_db_connection():
    conn = sqlite3.connect('experiance.db')
    conn.row_factory = sqlite3.Row
    return conn

# Page d'inscription
@app.route('/inscription', methods=['GET', 'POST'])
def inscription():
    if request.method == 'POST':
        nom = request.form['nom']
        email = request.form['email']
        mot_de_passe = generate_password_hash(request.form['mot_de_passe'])
        
        conn = get_db_connection()
        try:
            conn.execute('INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)',
                         (nom, email, mot_de_passe))
            conn.commit()
            flash('Compte créé avec succès ! Connectez-vous.', 'success')
            return redirect(url_for('connexion'))
        except sqlite3.IntegrityError:
            flash('Cet email est déjà utilisé.', 'danger')
        finally:
            conn.close()
    return render_template('inscription.html')

# Page de connexion
@app.route('/connexion', methods=['GET', 'POST'])
def connexion():
    if request.method == 'POST':
        email = request.form['email']
        mot_de_passe = request.form['mot_de_passe']
        
        conn = get_db_connection()
        utilisateur = conn.execute('SELECT * FROM utilisateurs WHERE email = ?', (email,)).fetchone()
        conn.close()

        if utilisateur and check_password_hash(utilisateur['mot_de_passe'], mot_de_passe):
            session['user_id'] = utilisateur['id']
            session['user_name'] = utilisateur['nom']
            flash('Connexion réussie !', 'success')
            return redirect(url_for('profil'))
        else:
            flash('Email ou mot de passe incorrect.', 'danger')
    return render_template('connexion.html')

# Page profil
@app.route('/profil')
def profil():
    if 'user_id' in session:
        return f"Bienvenue {session['user_name']} ! <a href='/deconnexion'>Se déconnecter</a>"
    else:
        return redirect(url_for('connexion'))

# Déconnexion
@app.route('/deconnexion')
def deconnexion():
    session.clear()
    flash('Vous avez été déconnecté.', 'info')
    return redirect(url_for('connexion'))

if __name__ == '__main__':
    app.run(debug=True)
