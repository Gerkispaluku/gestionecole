/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package gestion_ecole;

import java.beans.PropertyChangeListener;
import java.beans.PropertyChangeSupport;
import java.io.Serializable;
import javax.persistence.Basic;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.NamedQueries;
import javax.persistence.NamedQuery;
import javax.persistence.Table;
import javax.persistence.Transient;

/**
 *
 * @author user
 */
@Entity
@Table(name = "fonction", catalog = "gestionecole", schema = "")
@NamedQueries({
    @NamedQuery(name = "Fonction.findAll", query = "SELECT f FROM Fonction f"),
    @NamedQuery(name = "Fonction.findByIdFonction", query = "SELECT f FROM Fonction f WHERE f.idFonction = :idFonction"),
    @NamedQuery(name = "Fonction.findByNomFonction", query = "SELECT f FROM Fonction f WHERE f.nomFonction = :nomFonction")})
public class Fonction implements Serializable {
    @Transient
    private PropertyChangeSupport changeSupport = new PropertyChangeSupport(this);
    private static final long serialVersionUID = 1L;
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Basic(optional = false)
    @Column(name = "IdFonction")
    private Integer idFonction;
    @Column(name = "NomFonction")
    private String nomFonction;

    public Fonction() {
    }

    public Fonction(Integer idFonction) {
        this.idFonction = idFonction;
    }

    public Integer getIdFonction() {
        return idFonction;
    }

    public void setIdFonction(Integer idFonction) {
        Integer oldIdFonction = this.idFonction;
        this.idFonction = idFonction;
        changeSupport.firePropertyChange("idFonction", oldIdFonction, idFonction);
    }

    public String getNomFonction() {
        return nomFonction;
    }

    public void setNomFonction(String nomFonction) {
        String oldNomFonction = this.nomFonction;
        this.nomFonction = nomFonction;
        changeSupport.firePropertyChange("nomFonction", oldNomFonction, nomFonction);
    }

    @Override
    public int hashCode() {
        int hash = 0;
        hash += (idFonction != null ? idFonction.hashCode() : 0);
        return hash;
    }

    @Override
    public boolean equals(Object object) {
        // TODO: Warning - this method won't work in the case the id fields are not set
        if (!(object instanceof Fonction)) {
            return false;
        }
        Fonction other = (Fonction) object;
        if ((this.idFonction == null && other.idFonction != null) || (this.idFonction != null && !this.idFonction.equals(other.idFonction))) {
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        return "gestion_employes.Fonction[ idFonction=" + idFonction + " ]";
    }

    public void addPropertyChangeListener(PropertyChangeListener listener) {
        changeSupport.addPropertyChangeListener(listener);
    }

    public void removePropertyChangeListener(PropertyChangeListener listener) {
        changeSupport.removePropertyChangeListener(listener);
    }
    
}
