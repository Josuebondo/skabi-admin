SELECT
    SUM(m.quantite * m.prix) AS stock_recu
FROM mouvements m
WHERE (
        (m.type = 'entrée' AND m.entrepot_id = 3)
    OR (m.type = 'transfert' AND m.destination_id = 3)
)
    AND m.statut IN ('validé','vérifié');