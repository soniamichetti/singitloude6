<?php
// tests/TestAuthentification.php
// Inclure les fichiers nécessaires
session_start();
require_once '../modele/security.php';

class TestAuthentification {
    private $testsPassed = 0;
    private $testsFailed = 0;
    
    public function runAllTests() {
        echo "Exécution des tests d'authentification...\n";
        
        $this->testIsLoggedIn();
        $this->testIsAdmin();
        
        echo "\nRésumé des tests: \n";
        echo "Tests réussis: " . $this->testsPassed . "\n";
        echo "Tests échoués: " . $this->testsFailed . "\n";
        
        return $this->testsFailed === 0;
    }
    
    private function testIsLoggedIn() {
        echo "\nTest de la fonction isLoggedIn():\n";
        
        // Test 1: Utilisateur non connecté
        $_SESSION = [];
        $result1 = isLoggedIn();
        $this->assertFalse($result1, "isLoggedIn() devrait retourner false quand aucun utilisateur n'est connecté");
        
        // Test 2: Utilisateur connecté
        $_SESSION['pseudo'] = 'testUser';
        $result2 = isLoggedIn();
        $this->assertTrue($result2, "isLoggedIn() devrait retourner true quand un utilisateur est connecté");
    }
    
    private function testIsAdmin() {
        echo "\nTest de la fonction isAdmin():\n";
        
        // Test 1: Utilisateur non connecté
        $_SESSION = [];
        $result1 = isAdmin();
        $this->assertFalse($result1, "isAdmin() devrait retourner false quand aucun utilisateur n'est connecté");
        
        // Test 2: Utilisateur connecté mais pas admin
        $_SESSION['pseudo'] = 'testUser';
        $_SESSION['userType'] = 'utilisateur';
        $result2 = isAdmin();
        $this->assertFalse($result2, "isAdmin() devrait retourner false quand l'utilisateur n'est pas admin");
        
        // Test 3: Utilisateur admin
        $_SESSION['pseudo'] = 'testAdmin';
        $_SESSION['userType'] = 'admin';
        $result3 = isAdmin();
        $this->assertTrue($result3, "isAdmin() devrait retourner true quand l'utilisateur est admin");
    }
    
    private function assertTrue($condition, $message) {
        if ($condition === true) {
            echo "✓ Test réussi: $message\n";
            $this->testsPassed++;
        } else {
            echo "✗ Test échoué: $message\n";
            $this->testsFailed++;
        }
    }
    
    private function assertFalse($condition, $message) {
        if ($condition === false) {
            echo "✓ Test réussi: $message\n";
            $this->testsPassed++;
        } else {
            echo "✗ Test échoué: $message\n";
            $this->testsFailed++;
        }
    }
}

// Si ce fichier est exécuté directement, lancer les tests
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    $tester = new TestAuthentification();
    $tester->runAllTests();
}