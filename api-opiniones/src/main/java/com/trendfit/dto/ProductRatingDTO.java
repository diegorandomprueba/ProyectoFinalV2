package com.trendfit.dto;

import java.util.Objects;

public class ProductRatingDTO {
    private Long productId;
    private Double averageRating;
    private Integer numRatings;
    private Double weightedRating;

    // Constructor vacío
    public ProductRatingDTO() {
    }

    // Constructor completo
    public ProductRatingDTO(Long productId, Double averageRating, Integer numRatings, Double weightedRating) {
        this.productId = productId;
        this.averageRating = averageRating;
        this.numRatings = numRatings;
        this.weightedRating = weightedRating;
    }

    // Getters y setters
    public Long getProductId() {
        return productId;
    }

    public void setProductId(Long productId) {
        this.productId = productId;
    }

    public Double getAverageRating() {
        return averageRating;
    }

    public void setAverageRating(Double averageRating) {
        this.averageRating = averageRating;
    }

    public Integer getNumRatings() {
        return numRatings;
    }

    public void setNumRatings(Integer numRatings) {
        this.numRatings = numRatings;
    }

    public Double getWeightedRating() {
        return weightedRating;
    }

    public void setWeightedRating(Double weightedRating) {
        this.weightedRating = weightedRating;
    }

    // Equals, hashCode y toString
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        ProductRatingDTO that = (ProductRatingDTO) o;
        return Objects.equals(productId, that.productId);
    }

    @Override
    public int hashCode() {
        return Objects.hash(productId);
    }

    @Override
    public String toString() {
        return "ProductRatingDTO{" +
                "productId=" + productId +
                ", averageRating=" + averageRating +
                ", numRatings=" + numRatings +
                ", weightedRating=" + weightedRating +
                '}';
    }
}