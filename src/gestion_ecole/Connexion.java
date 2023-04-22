/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gestion_ecole;
import java.sql.*; 
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JOptionPane;
/**
 *
 * @author user
 */
public class Connexion {
    //String urlPilote="com.mysql.jdbc.Driver ";  //chemin pour charger le pilote
   // String urlBaseDonnees="jdbc:mysql://localhost:3306/gestionecole";   //chemin de connexion a la basede donnees
   public Connection con;
    
    public  Connexion() {
    
        try{
            //chargement du pilote
            Class.forName("com.mysql.jdbc.Driver"); 
              //connexion a la base de donnees
             con=DriverManager.getConnection("jdbc:mysql://localhost:3306/gestionecole","root","");
        
            System.out.println("connexion reussi avec succes");
        }
        catch(ClassNotFoundException ex){
            System.out.println(ex.getMessage());
        } catch (SQLException ex) {
            Logger.getLogger(Connexion.class.getName()).log(Level.SEVERE, null, ex);
        }
  
       
    
    }

   Connection ObtenirConnexion(){
   return con;
  }    
}
