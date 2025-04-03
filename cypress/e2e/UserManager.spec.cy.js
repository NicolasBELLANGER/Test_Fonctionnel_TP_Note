describe('Scénario utilisateur complet - Gestion des utilisateurs', () => {
  const nomInitial = 'Nicolas';
  const emailInitial = 'nicolas@example.com';
  const nomModifie = 'Nico.';
  const emailModifie = 'nico@example.com';

  beforeEach(() => {
    cy.visit('http://localhost/tpnote/index.html');
  });

  it('Ajoute un utilisateur via l’interface', () => {
    cy.get('#name').type(nomInitial);
    cy.get('#email').type(emailInitial);
    cy.get('form').submit();

    cy.wait(500); // attendre que l'affichage se fasse

    cy.get('#userList').should('contain.text', `${nomInitial} (${emailInitial})`);
  });

  it('Modifie les informations de l’utilisateur', () => {
    cy.wait(500); // attendre que les utilisateurs soient visibles

    cy.get('#userList')
      .contains(`${nomInitial} (${emailInitial})`)
      .parent()
      .find('button')
      .first()
      .click();

    cy.get('#name').clear().type(nomModifie);
    cy.get('#email').clear().type(emailModifie);
    cy.get('form').submit();

    cy.wait(500);

    cy.get('#userList').should('contain.text', `${nomModifie} (${emailModifie})`);
  });

  it('Supprime l’utilisateur', () => {
    cy.wait(500);

    cy.get('#userList')
      .contains(`${nomModifie} (${emailModifie})`)
      .parent()
      .find('button')
      .last()
      .click();

    cy.wait(500);

    cy.get('#userList').should('not.contain.text', `${nomModifie} (${emailModifie})`);
  });
});
