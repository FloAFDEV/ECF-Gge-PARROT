<?php
require_once "models/API.manager.php";
require_once "models/Model.php";

// Définition des constantes pour les codes de statut HTTP
define("HTTP_OK", 200);
define("HTTP_BAD_REQUEST", 400);
define("HTTP_INTERNAL_SERVER_ERROR", 500);

class APIController
{
    private $apiManager;

    public function __construct()
    {
        $this->apiManager = new APIManager();
    }

    private function sendJSONResponse($data, $statusCode)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function getAllAnnonces($idAnnonces = null)
    {
        try {
            $linesAnnonces = ($idAnnonces !== null) ? $this->apiManager->getAllAnnonces($idAnnonces) : $this->apiManager->getAllAnnonces();
            $this->sendJSONResponse($linesAnnonces, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAnnonceByID($id)
    {
        try {
            $annonce = $this->apiManager->getAnnonceByID($id);
            $this->sendJSONResponse($annonce, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getModels()
    {
        try {
            $models = $this->apiManager->getDBModels();
            $this->sendJSONResponse($models, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBrands()
    {
        try {
            $brands = $this->apiManager->getDBBrands();
            $this->sendJSONResponse($brands, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getGarage()
    {
        try {
            $garage = $this->apiManager->getDBGarage();
            $this->sendJSONResponse($garage, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getImages()
    {
        try {
            $images = $this->apiManager->getDBImages();
            $this->sendJSONResponse($images, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTestimonials()
    {
        try {
            $testimonials = $this->apiManager->getDBTestimonials();
            $this->sendJSONResponse($testimonials, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getValidTestimonials()
    {
        try {
            $validTestimonials = $this->apiManager->getDBValidTestimonials(true);
            $this->sendJSONResponse($validTestimonials, HTTP_OK);
        } catch (PDOException $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => "Database error: " . $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDBTestimonialId($testimonialId)
    {
        try {
            $validTestimonials = $this->apiManager->getDBTestimonialId($testimonialId);
            $this->sendJSONResponse($validTestimonials, HTTP_OK);
        } catch (PDOException $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => "Database error: " . $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertTestimonial()
    {
        try {
            $formData = json_decode(file_get_contents('php://input'), true);
            if ($formData === null) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Aucunes données transmises dans le formulaire"], HTTP_BAD_REQUEST);
                return;
            }
            $result = $this->apiManager->insertTestimonial($formData);
            var_dump($result);
            if ($result === true) {
                $this->sendJSONResponse(["success" => "Votre témoignage a bien été enregistré"], HTTP_OK);
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Une erreur est survenue lors de l'enregistrement de votre témoignage"], HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateTestimonialValidation($testimonialId, $newValidity)
    {
        try {
            if (!isset($testimonialId) || !isset($newValidity)) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Données invalides"], HTTP_BAD_REQUEST);
                return;
            }
            $result = $this->apiManager->updateTestimonialValidation($testimonialId, $newValidity);
            var_dump($result);
            if ($result) {
                $this->sendJSONResponse(["message" => "Témoignage mis à jour avec succès"], HTTP_OK);
                return true;
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Échec de la mise à jour du témoignage"], HTTP_INTERNAL_SERVER_ERROR);
                return false;
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
    }


    public function deleteTestimonial($testimonialId)
    {
        try {
            if (!isset($testimonialId)) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Données incomplètes pour la suppression du témoignage"], HTTP_BAD_REQUEST);
                return;
            }
            $result = $this->apiManager->deleteTestimonial($testimonialId);
            if ($result) {
                $this->sendJSONResponse(["message" => "Témoignage supprimé avec succès"], HTTP_OK);
                return true;
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Échec de la suppression du témoignage"], HTTP_INTERNAL_SERVER_ERROR);
                return false;
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
    }

    public function getOpening()
    {
        try {
            $opening = $this->apiManager->getDBOpening();
            $this->sendJSONResponse($opening, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getGarageServices()
    {
        try {
            $services = $this->apiManager->getDBGarageServices();
            $this->sendJSONResponse($services, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOptions()
    {
        try {
            $options = $this->apiManager->getDBOptions();
            $this->sendJSONResponse($options, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getManufactureYears()
    {
        try {
            $manufactureYears = $this->apiManager->getDBManufactureYears();
            $this->sendJSONResponse($manufactureYears, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEnergyType()
    {
        try {
            $energy = $this->apiManager->getDBEnergyType();
            $this->sendJSONResponse($energy, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCars()
    {
        try {
            $annonce = $this->apiManager->getDBCars();
            $this->sendJSONResponse($annonce, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllMessageAnnonce()
    {
        try {
            $messages = $this->apiManager->getDBAllMessageAnnonce();
            $this->sendJSONResponse($messages, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getResetPassword()
    {
        try {
            $password = $this->apiManager->getDBResetPassword();
            $this->sendJSONResponse($password, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getUsers()
    {
        try {
            $users = $this->apiManager->getDBUsers();
            $this->sendJSONResponse($users, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContactMessage()
    {
        try {
            $password = $this->apiManager->getDBContactMessage();
            $this->sendJSONResponse($password, HTTP_OK);
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertContactMessage()
    {
        try {
            $formData = json_decode(file_get_contents('php://input'), true);
            if ($formData === null) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Aucunes données transmises dans le formulaire"], HTTP_BAD_REQUEST);
                return;
            }
            $result = $this->apiManager->insertContactMessage($formData);
            if ($result === true) {
                $this->sendJSONResponse(["success" => "Votre message a bien été envoyé"], HTTP_OK);
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Une erreur est survenue lors de l'envoi de votre message"], HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertMessageAnnonce()
    {
        try {
            $formData = json_decode(file_get_contents('php://input'), true);
            if ($formData === null || !isset($formData['Id_CarAnnonce'])) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Aucune donnée valide transmise dans le formulaire ou l'ID de l'annonce de voiture est manquant"], HTTP_BAD_REQUEST);
                return;
            }

            $Id_CarAnnonce = $formData['Id_CarAnnonce'];
            $result = $this->apiManager->insertMessageAnnonce($Id_CarAnnonce, $formData);
            if ($result === true) {
                $this->sendJSONResponse(["success" => "Votre message a bien été envoyé"], HTTP_OK);
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Une erreur est survenue lors de l'envoi de votre message"], HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createAnnonce()
    {
        try {
            $formData = json_decode(file_get_contents('php://input'), true);
            if ($formData === null) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Aucune donnée valide transmise dans le formulaire"], HTTP_BAD_REQUEST);
                return;
            }
            $result = $this->apiManager->createAnnonce($formData);
            if ($result === true) {
                $this->sendJSONResponse(["success" => "L'annonce a bien été ajoutée"], HTTP_OK);
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Une erreur est survenue lors de l'ajout de l'annonce"], HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAnnonce($id)
    {
        try {
            $formData = json_decode(file_get_contents('php://input'), true);
            if ($formData === null) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Aucune donnée valide transmise dans le formulaire"], HTTP_BAD_REQUEST);
                return;
            }

            $result = $this->apiManager->updateAnnonce($id, $formData);
            if ($result === true) {
                $this->sendJSONResponse(["success" => "L'annonce a bien été mise à jour"], HTTP_OK);
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Une erreur est survenue lors de la mise à jour de l'annonce"], HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteAnnonce($annonceId)
    {
        try {
            if (!isset($annonceId)) {
                http_response_code(HTTP_BAD_REQUEST);
                $this->sendJSONResponse(["error" => "Données incomplètes pour la suppression de l'annonce"], HTTP_BAD_REQUEST);
                return;
            }
            $result = $this->apiManager->deleteAnnonce($annonceId);
            if ($result) {
                $this->sendJSONResponse(["message" => "Annonce supprimé avec succès"], HTTP_OK);
                return true;
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Échec de la suppression de l'annonce"], HTTP_INTERNAL_SERVER_ERROR);
                return false;
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
    }

    public function updateValidationStatus($annonceId, $newValidity)
    {
        try {
            $success = $this->apiManager->updateValidationStatus($annonceId, $newValidity);
            if ($success) {
                $updatedData = $this->apiManager->getAnnonceById($annonceId);
                if ($updatedData) {
                    $this->sendJSONResponse($updatedData, HTTP_OK);
                } else {
                    http_response_code(404);
                    $this->sendJSONResponse(["error" => "Annonce mise à jour introuvable"], 404);
                }
            } else {
                http_response_code(HTTP_INTERNAL_SERVER_ERROR);
                $this->sendJSONResponse(["error" => "Erreur lors de la mise à jour du statut de validité"], HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
            $this->sendJSONResponse(["error" => $e->getMessage()], HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
